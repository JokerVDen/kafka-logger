<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Providers;

use Illuminate\Support\ServiceProvider;
use JokerVDen\KafkaLogger\Services\KafkaLogger;
use JokerVDen\KafkaLogger\Contracts\KafkaLoggerContract;

class KafkaLoggerServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/kafka-logger.php', 'kafka-logger');

        $this->app->singleton(KafkaLoggerContract::class, function () {
            return new KafkaLogger();
        });
    }

    /**
     * Boot the package
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/kafka-logger.php' => config_path('kafka-logger.php'),
        ]);
    }
}
