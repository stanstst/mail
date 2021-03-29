<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintsRecipients extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Recipients must be array of objects';
}