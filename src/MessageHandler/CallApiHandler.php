<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Messenger\Event\SmsStored;
use App\Repository\SmsRepository;
use App\Entity\SmsServiceProvider;
use App\Repository\SmsServiceProviderRepository;
use App\Entity\Sms;

class CallApiHandler implements MessageHandlerInterface
{
    private $entityManager;

    private $smsRepository;

    private $smsServiceProviderRepository;

    private $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        SmsRepository $smsRepository,
        SmsServiceProviderRepository $smsServiceProviderRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->smsRepository = $smsRepository;
        $this->smsServiceProviderRepository = $smsServiceProviderRepository;
        $this->logger = $logger;
    }

    public function __invoke(SmsStored $obj)
    {
        $id = $obj->getId();

        // retrieve data from DB
        $sms = $this->smsRepository->find($id);
        if (!$sms) {
            // TODO: $this->logger->error();
            $sms->setStatus(Sms::SMS_FAILED);
            $this->entityManager->persist($sms);
            $this->entityManager->flush();

            // throw unrecoverable exception to avoid retry
            throw new UnrecoverableMessageHandlingException();
        }

        try {
            $response = HttpClient::create()->request(
                'GET',
                $sms->getSmsServiceProvider()->getGateway(),
                [
                    'query' => [
                        'number' => $sms->getNumber(),
                        'body' => $sms->getText(),
                    ]
                ]
            );

            $response->getHeaders(true); // throw exception when HTTP status is not 2xx

        } catch (\RuntimeException $e) {
            // TODO: $this->logger->debug()
            // TODO: a better way to switch between service provider. round robin
            $nextSmsServiceProviderId = ($sms->getSmsServiceProvider()->getId()
                % count($this->smsServiceProviderRepository->findAll())) + 1;
            //$this-logger->debug(' ... ');
            $sms->setSmsServiceProvider(
                $this->smsServiceProviderRepository->find($nextSmsServiceProviderId)
            );
            // TODO: check whether it is the last try, set failed status for message
            $sms->setStatus(Sms::SMS_PENDING);

            // TODO: custom exception will needed
            throw new \RuntimeException();
        }

        $sms->setStatus(Sms::SMS_SUCCESS);

        $this->entityManager->persist($sms);
        $this->entityManager->flush(); 

        $this->logger->debug(' .... ');
    }
}

