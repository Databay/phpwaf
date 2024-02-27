<?php

namespace App\Exception;

use Exception;

class PayloadException extends Exception
{
    private $payloadFile;

    private $payloadStrictMatch;

    private $payloadBlockingType;

    public function __construct(
        string $payloadBlockingType,
        string $payloadFile,
        bool $payloadStrictMatch,
        string $message = '',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->payloadBlockingType = $payloadBlockingType;
        $this->payloadFile = $payloadFile;
        $this->payloadStrictMatch = $payloadStrictMatch;
    }

    public function getPayloadFile(): string
    {
        return $this->payloadFile;
    }

    public function isPayloadStrictMatch(): bool
    {
        return $this->payloadStrictMatch;
    }

    public function getPayloadBlockingType(): string
    {
        return $this->payloadBlockingType;
    }
}