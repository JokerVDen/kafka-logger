<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Contracts;

interface SourceTypeContract
{
    public function value(): string;
}
