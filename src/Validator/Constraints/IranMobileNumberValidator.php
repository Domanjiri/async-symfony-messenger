<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IranMobileNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        // regex credit: https://lamtakam.com/qanda/2211/-php#post-2217
        if (!preg_match('/^(?:98|\+98|0098|0)?9[0-9]{9}$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ number  }}', $value)
                ->addViolation();
        }
    }
}
 
