<?php

declare(strict_types=1);

namespace Tuurosung\Teller\Http;

use JsonException;

final readonly class TransportResponse
{
    /**
     * @param  array<string, mixed>|null  $json
     */
    public function __construct(
        private int $status,
        public string $body,
        public ?array $json
    ) {}


    public static function from(int $status, string $body): self
    {
        return new self($status, $body, self::decode($body));
    }


    /**
     * @return array<string, mixed>|null
     */
    private static function decode(string $body): ?array
    {
        if (trim($body) === '') {
            return null;
        }

        try {
            $decoded = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return null;
        }   

        return is_array($decoded) ? $decoded : null;        
    }


    public function isJson(): bool
    {
        return $this->json !== null;
    }


    public function serverError(): bool
    {
        return $this->status >= 500;
    }


    public function clientError(): bool
    {
        return $this->status >= 400  && $this->status  < 500;
    }


    public function string(string $key, string $default = ''): string
    {
        $value = $this->json[$key] ?? null;
        return is_scalar($value) ? (string) $value : $default;
    }


    public function nullableString(string $key): ?string
    {
        $value = $this->json[$key] ?? null;
        return is_scalar($value) ? (string) $value : null;
    }


    public function toArray(): array
    {
        return $this->json ?? [];
    }

}