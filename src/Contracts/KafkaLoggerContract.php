<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Contracts;

use Exception;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;
use JsonException;

interface KafkaLoggerContract
{
    /**
     * Log into the kafka
     *
     * @throws JsonException
     * @throws Exception
     */
    public function log(LogMessage $messageDto): void;
}
