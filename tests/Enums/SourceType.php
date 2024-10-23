<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Tests\Enums;

use JokerVDen\KafkaLogger\Contracts\SourceTypeContract;

enum SourceType: string implements SourceTypeContract
{
    case AUTH_SERVICE = 'auth_service';

    public function value(): string
    {
        return $this->value;
    }
}
