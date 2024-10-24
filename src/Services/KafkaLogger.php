<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Services;

use Exception;
use JokerVDen\KafkaLogger\Contracts\KafkaLoggerContract;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;
use JsonException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class KafkaLogger implements KafkaLoggerContract
{
    protected string $topic;

    public function __construct()
    {
        $this->topic = config('kafka-logger.topic', 'app-logs');
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function log(LogMessage $messageDto): void
    {
        $message = new Message(
            topicName: $this->topic,
            body: json_encode($messageDto->toArray(), JSON_THROW_ON_ERROR)
        );

        Kafka::publish()
            ->withMessage($message)
            ->send();
    }
}
