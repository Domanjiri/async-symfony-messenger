<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Sms;

class SmsNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // transfrom mobile number to 12 digits integer
        // ex. 091245.. to 9891245..
        $patterns = '/^(98|\+98|0098|0)?(9[0-9]{9})$/'; 
        $replace = '98$2';
        $data['number'] = preg_replace($patterns, $replace, $data['number']);

        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Sms;
    }
}

