<?php

namespace App\Exception;

use Exception;

class PayloadException extends Exception
{
    private $payloadFile;

    private $payloadStrictMatch;

    private $criticalPayload;

    public function __construct(
        string $payloadFile,
        bool $payloadStrictMatch,
        bool $criticalPayload,
        string $message = '',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->payloadFile = $payloadFile;
        $this->payloadStrictMatch = $payloadStrictMatch;
        $this->criticalPayload = $criticalPayload;
    }

    public function getPayloadFile(): string
    {
        return $this->payloadFile;
    }

    public function isPayloadStrictMatch(): bool
    {
        return $this->payloadStrictMatch;
    }

    public function isCriticalPayload(): bool
    {
        return $this->criticalPayload;
    }
}