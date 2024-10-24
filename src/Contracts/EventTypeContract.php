<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Contracts;

interface EventTypeContract
{
    public function value(): string;
}
