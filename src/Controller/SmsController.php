<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Envelope;
use App\Service\SmsIdempotency;
use App\Entity\Sms;
use App\Messenger\SmsStamp;

class SmsController extends AbstractController
{
    private $request;

    private $idempotency;

    private $validator;

    private $logger;

    private $isDebug;

    public function __construct(
        RequestStack $request,
        SmsIdempotency $idempotency,
        ValidatorInterface $validator,
        LoggerInterface $logger,
        bool $isDebug
    ) {
        $this->request = $request;
        $this->idempotency = $idempotency;
        $this->validator = $validator;
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

    public function send(): JsonResponse
    {
        $sms = new Sms();

        $sms->setNumber($this->request->getCurrentRequest()->query->get('number'))
            ->setText($this->request->getCurrentRequest()->query->get('body'))
            ->setTimestamp($sms->getTimestamp())
            ->setStatus(Sms::SMS_PENDING);

        // data validity checks by annotaions
        $errors = $this->validator->validate($sms);
        if (count($errors) > 0) {
            // TODO: $this->logger->info();
            return $this->json(['status' => false, 'error' => (string)$errors]);
        }

        // assumtion1: we need idempotency
        // perform just one action on identical request within specific interval
        if (!$this->idempotency->check($sms)) {
            // assumption2: availability of Redis is guaranteed.
            // in the favor of handling more throughput, we send message directly to
            // the queue, to be processed in asynchronous way.
            //
            // serializing contains normalization (transform all variation of mobile
            // number to 12 digits number (+989.., 0911.. to 98911..), which will be
            // used for index in MySql table).
            //
            // note1: Redis adaptor does not support deleyed message
            // note2: we will need Redis server (^5.0)
            $stamp = new SmsStamp();
            $stamp->setTimestamp($stamp->getTimestamp()); // now

            $this->dispatchMessage(new Envelope($sms, [$stamp])); 

            $this->logger->debug(' .. ');
        }

        return $this->json(['status' => true, 'id' => $sms->getUniqueId()]);
    }
}

