<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Tests;

use JokerVDen\KafkaLogger\Providers\KafkaLoggerServiceProvider;
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
            'user_logged_in',
            ['username' => 'johndoe'],
            'auth-service',
            123,
            'req-001',
            'evt-001'
        );

        $this->assertEquals('user_logged_in', $message->eventType);
        $this->assertEquals(['username' => 'johndoe'], $message->data);
        $this->assertEquals(123, $message->userId);
        $this->assertEquals('auth-service', $message->source);
        $this->assertEquals('req-001', $message->requestId);
        $this->assertEquals('evt-001', $message->eventId);
        $this->assertNotNull($message->createdAt);
    }

    public function testNormalizeDataHandlesVariousFormats()
    {
        $messageFromArray = new LogMessage('event', ['key' => 'value'], 'auth-service');
        $messageFromObject = new LogMessage('event', (object)['key' => 'value'], 'auth-service');
        $messageFromStdClass = new LogMessage('event', new \stdClass(), 'auth-service');
        $messageFromString = new LogMessage('event', 'simple message', 'auth-service');

        $this->assertEquals(['key' => 'value'], $messageFromArray->data);
        $this->assertEquals(['key' => 'value'], $messageFromObject->data);
        $this->assertEquals([], $messageFromStdClass->data);
        $this->assertEquals(['message' => 'simple message'], $messageFromString->data);
    }
}
