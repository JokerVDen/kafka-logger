# Kafka Logger for Laravel

**KafkaLogger** is a Laravel package designed to log application events to Kafka using the `mateusjunges/laravel-kafka`
package. It provides a simple way to structure and send log messages with support for UUID generation and customizable
message data.

## Features

- Log events to Kafka topics
- Automatically generate UUIDs for `requestId` and `eventId` using UUID v4
- Customizable data payloads for each log event
- Easy integration with Laravel using Service Providers

## Requirements

- PHP 8.2+
- Laravel 10.0+
- Kafka instance (for actual logging)

## Installation

1. First, require the package in your Laravel project:

   Add to the `composer.json`:
   ```json
       "repositories": [
           {
               "type": "vcs",
               "url": "https://github.com/JokerVDen/kafka-logger"
           }
       ]
   ```

   Execute:

   ```bash
   composer require jokervden/kafka-logger
   ```
2. Publish the configuration (optional):
   You can publish the configuration file to customize the Kafka topic:
   ```bash
   php artisan vendor:publish --provider="JokerVDen\KafkaLogger\KafkaLoggerServiceProvider"
   ```

This will create a configuration file `config/kafka-logger.php` where you can set the default Kafka topic.

## Configuration

Ensure your Kafka configuration is correctly set up in your .env file. For example:

```env
KAFKA_BROKERS=localhost:9092
```

- KAFKA_BROKERS: Define the Kafka broker URL.

## Usage

#### Sending a Log Message to Kafka

You can send log messages by using the KafkaLogger service. Here’s an example of how to create and send a log message:

```php
use Ramsey\Uuid\Uuid;
use JokerVDen\KafkaLogger\Contracts\EventTypeContract;
use JokerVDen\KafkaLogger\Contracts\SourceTypeContract;
use JokerVDen\KafkaLogger\Services\KafkaLogger;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;

enum EventType: string implements EventTypeContract
{
    case USER_LOGGED_IN = 'user_logged_in';

    public function value(): string
    {
        return $this->value;
    }
}

enum SourceType: string implements SourceTypeContract
{
    case AUTH_SERVICE = 'auth_service';

    public function value(): string
    {
        return $this->value;
    }
}

$kafkaLogger = app(KafkaLogger::class);

$message = new LogMessage(
   eventType: EventType::USER_LOGGED_IN,
   data: ['some data' => 'asdfasdf'],
   source: SourceType::AUTH_SERVICE,
   userId: 1,
   requestId: Uuid::uuid4(),
);

$kafkaLogger->log($message);
```

#### Automatic UUID Generation

The package simplifies the process of generating requestId using UUID v4. If you don’t pass the requestId 
when constructing the LogMessage, they will be generated automatically:

```php
use JokerVDen\KafkaLogger\Services\KafkaLogger;
use JokerVDen\KafkaLogger\ValueObjects\LogMessage;

$kafkaLogger = app(KafkaLogger::class);

$message = new LogMessage(
   eventType: EventType::USER_LOGGED_IN,
   data: ['some data' => 'asdfasdf'],
   source: SourceType::AUTH_SERVICE,
   userId: 1,
   userId: 42
);

$kafkaLogger->log($message);
```

#### Customization

You can customize the Kafka topic and other configurations by publishing the package’s configuration file:

```bash
php artisan vendor:publish --provider="JokerVDen\KafkaLogger\KafkaLoggerServiceProvider"
```

This will create config/kafka-logger.php where you can modify the Kafka topic and other relevant settings:

```php
return [
    'topic' => env('KAFKA_LOGGER_TOPIC', 'app-logs'),
];
```

## Running Tests

This package uses orchestra/testbench for testing.

To run tests, execute:

```bash
./vendor/bin/phpunit
```
