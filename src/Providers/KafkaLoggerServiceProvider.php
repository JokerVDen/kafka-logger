<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Providers;

use Illuminate\Support\ServiceProvider;
use JokerVDen\KafkaLogger\Services\KafkaLogger;
use JokerVDen\KafkaLogger\Contracts\KafkaLoggerContract;

class KafkaLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/kafka-logger.php', 'kafka-logger');

        $this->app->singleton(KafkaLoggerContract::class, function () {
            return new KafkaLogger();
        });
    }
}
