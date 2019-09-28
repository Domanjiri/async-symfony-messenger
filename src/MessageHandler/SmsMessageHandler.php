<?php

namespace App\MessageHandler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use App\Entity\Sms;
use App\Repository\SmsServiceProviderRepository;
use App\Messenger\Event\SmsStored;

class SmsMessageHandler implements MessageHandlerInterface
{
    private $eventBus;

    private $entityManager;

    private $smsServiceProviderRepository;

    private $logger;

    public function __construct(
        MessageBusInterface $eventBus,
        EntityManagerInterface $entityManager,
        SmsServiceProviderRepository $smsServiceProviderRepository,
        LoggerInterface $logger
    ) {
        $this->eventBus = $eventBus;
        $this->entityManager = $entityManager;
        $this->smsServiceProviderRepository = $smsServiceProviderRepository;
        $this->logger = $logger;
    }

    public function __invoke(Sms $sms)
    {
        // TODO: get service provider with higher score to increase the chance of sound call in first try
        $choosedSmsServiceProviderId = 1;

        $sms->setSmsServiceProvider(
            $this->smsServiceProviderRepository->find($choosedSmsServiceProviderId)
        );
        // try to store message
        $this->entityManager->persist($sms);
        $this->entityManager->flush();

        $this->logger->debug(' ... ');

        // then, and in case of successful transaction, dispatch event to handling third party API calls
        $event = new SmsStored($sms->getId());
        $this->eventBus->dispatch(
            (new Envelope($event))
                ->with(new DispatchAfterCurrentBusStamp())
        );
    }
}

