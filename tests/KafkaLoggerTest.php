<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Tests;

use JokerVDen\KafkaLogger\Providers\KafkaLoggerServiceProvider;
use Junges\Kafka\Message\Message;
use Orchestra\Testbench\TestCase;
use JokerVDen\KafkaLogger\Services\KafkaLogger;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;
use Junges\Kafka\Facades\Kafka;

class KafkaLoggerTest extends TestCase
{
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('kafka.brokers', 'localhost:9092');
    }

    protected function getPackageProviders($app): array
    {
        return [KafkaLoggerServiceProvider::class];
    }

    public function testLogMessageIsSentToKafka(): void
    {
        Kafka::fake();

        $logger = app(KafkaLogger::class);

        $messageDto = new LogMessage(
            'user_logged_in',
            ['username' => 'johndoe'],
            'auth-service',
            123,
            'req-001',
            'evt-001'
        );

        $logger->log($messageDto);

        $message = new Message(
            topicName: config('kafka-logger.topic'),
            body: json_encode($messageDto->toArray(), JSON_THROW_ON_ERROR)
        );

        Kafka::assertPublishedOn(config('kafka-logger.topic'), $message);
        Kafka::assertPublishedTimes();
    }
}
