<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Tests;

use JokerVDen\KafkaLogger\Providers\KafkaLoggerServiceProvider;
use JokerVDen\KafkaLogger\Tests\Enums\EventType;
use JokerVDen\KafkaLogger\Tests\Enums\SourceType;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;
use Orchestra\Testbench\TestCase;

class LogMessageTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [KafkaLoggerServiceProvider::class];
    }

    public function testLogMessageConstruction(): void
    {
        $message = new LogMessage(
            EventType::USER_LOGGED_IN,
            ['username' => 'johndoe'],
            SourceType::AUTH_SERVICE,
            123,
            'req-001',
        );

        $this->assertEquals(EventType::USER_LOGGED_IN, $message->eventType);
        $this->assertEquals(['username' => 'johndoe'], $message->data);
        $this->assertEquals(123, $message->userId);
        $this->assertEquals(SourceType::AUTH_SERVICE, $message->source);
        $this->assertEquals('req-001', $message->requestId);
        $this->assertNotNull($message->createdAt);
    }

    public function testNormalizeDataHandlesVariousFormats()
    {
        $messageFromArray = new LogMessage(EventType::USER_LOGGED_IN, ['key' => 'value'], SourceType::AUTH_SERVICE);
        $messageFromObject = new LogMessage(EventType::USER_LOGGED_IN, (object)['key' => 'value'], SourceType::AUTH_SERVICE);
        $messageFromStdClass = new LogMessage(EventType::USER_LOGGED_IN, new \stdClass(), SourceType::AUTH_SERVICE);
        $messageFromString = new LogMessage(EventType::USER_LOGGED_IN, 'simple message', SourceType::AUTH_SERVICE);

        $this->assertEquals(['key' => 'value'], $messageFromArray->data);
        $this->assertEquals(['key' => 'value'], $messageFromObject->data);
        $this->assertEquals([], $messageFromStdClass->data);
        $this->assertEquals(['message' => 'simple message'], $messageFromString->data);
    }
}
