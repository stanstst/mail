<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ConstraintsRecipientsValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ConstraintsRecipients) {
            throw new UnexpectedTypeException($constraint, ConstraintsRecipients::class);
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        if (count($value) === 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', print_r($value))
                ->addViolation();
        }

        // @todo validate each email in the list
    }
}