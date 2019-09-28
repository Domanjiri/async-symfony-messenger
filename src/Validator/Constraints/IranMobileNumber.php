<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IranMobileNumber extends Constraint
{
    public $message = 'The number is not valid';

    public function validatedBy()
    {
        return \get_class($this).'Validator';
    }
}
