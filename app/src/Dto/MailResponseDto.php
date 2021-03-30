<?php

namespace App\Dto;

class MailResponseDto
{
    /**
     * @var int
     */
    public $resourceId;

    public $errors = [];

    public function __construct(?int $resourceId, array $errors)
    {
        $this->resourceId = $resourceId;
        $this->errors = $errors;
    }

}