<?php

declare(strict_types=1);

namespace JokerVDen\KafkaLogger\ValueObjects;

use Carbon\Carbon;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

readonly class LogMessage
{
    public string $eventType;
    public array $data;
    public ?int $userId;
    public ?string $source;
    public ?string $requestId;
    public ?string $eventId;
    public string $createdAt;

    public function __construct(
        string $eventType,
        string|object|array $data,
        string $source,
        ?int $userId = null,
        ?string $requestId = null,
        ?string $eventId = null,
        ?Carbon $createdAt = null,
    ) {
        $this->eventType = $eventType;
        $this->data = $this->convertDataToArray($data);
        $this->userId = $userId;
        $this->source = $source;
        $this->requestId = $requestId ?: Uuid::uuid4()->toString();
        $this->eventId = $eventId ?: Uuid::uuid4()->toString();
        $this->createdAt = $createdAt?->toISOString() ?: Carbon::now()->toISOString();
    }

    /**
     * Convert data into array
     *
     * @param string|object|array $data
     *
     * @return string[]
     */
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

    /**
     * Converts the properties to an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'event_type' => $this->eventType,
            'data' => $this->data,
            'user_id' => $this->userId,
            'source' => $this->source,
            'request_id' => $this->requestId,
            'event_id' => $this->eventId,
            'created_at' => $this->createdAt,
        ];
    }
}
