<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\Tests\Enums;

use JokerVDen\KafkaLogger\Contracts\EventTypeContract;

enum EventType: string implements EventTypeContract
{
    case USER_LOGGED_IN = 'user_logged_in';

    public function value(): string
    {
        return $this->value;
    }
}
