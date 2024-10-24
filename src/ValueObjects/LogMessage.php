<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;
use JokerVDen\KafkaLogger\Contracts\EventTypeContract;
use JokerVDen\KafkaLogger\Contracts\SourceTypeContract;
use Ramsey\Uuid\Uuid;

readonly class LogMessage
{
    public EventTypeContract $eventType;
    public array $data;
    public ?int $userId;
    public SourceTypeContract $source;
    public ?string $requestId;
    public ?string $eventId;
    public string $createdAt;

    public function __construct(
        EventTypeContract $eventType,
        string|object|array $data,
        SourceTypeContract $source,
        ?int $userId = null,
        ?string $requestId = null,
        ?Carbon $createdAt = null,
    ) {
        $this->eventType = $eventType;
        $this->data = $this->convertDataToArray($data);
        $this->userId = $userId;
        $this->source = $source;
        $this->requestId = $requestId ?: Uuid::uuid4()->toString();
        $this->eventId = Uuid::uuid4()->toString();
        $this->createdAt = $createdAt?->toISOString() ?: Carbon::now()->toISOString();
    }

    protected function convertDataToArray(string|object|array $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        if (is_object($data)) {
            return json_decode(json_encode($data), true);
        }

        if (is_string($data)) {
            return ['message' => $data];
        }

        throw new InvalidArgumentException('Invalid data format. Expected array, object, or string.');
    }

    public function toArray(): array
    {
        return [
            'event_type' => $this->eventType->value(),
            'data' => $this->data,
            'user_id' => $this->userId,
            'source' => $this->source->value(),
            'request_id' => $this->requestId,
            'event_id' => $this->eventId,
            'created_at' => $this->createdAt,
        ];
    }
}
