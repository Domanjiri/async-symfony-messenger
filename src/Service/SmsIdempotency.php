<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use App\Entity\Sms;

class SmsIdempotency
{
    private $cache;

    private $idempotencyTime;

    public function __construct(AdapterInterface $cache, $idempotencyTime)
    {
        $this->cache = $cache;
        $this->idempotencyTime = $idempotencyTime;
    }

    public function check(Sms $sms): bool
    {
        // we use base64 encoded of concatenated (number + text) as cache-id
        // due to limitaion on length of sms number and body, the length of
        // cache-id may not make a serious trouble
        $concatenated = $sms->getNumber() . $sms->getText();
        $cacheId = base64_encode($concatenated);

        $cachedItem = $this->cache->getItem($cacheId);
        $cacheStatus = (bool)$cachedItem->isHit();
        // set cache 
        if (!$cacheStatus) {
            $cachedItem->set($sms->getUniqueId());
            $cachedItem->expiresAfter((int) $this->idempotencyTime);
            $this->cache->save($cachedItem);
        } else {
            $sms->setUniqueId($cachedItem->get());
        }

        return (bool)$cachedItem->isHit();
    }
}

