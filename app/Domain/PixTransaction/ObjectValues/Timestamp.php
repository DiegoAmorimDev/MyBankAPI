<?php

namespace App\Domain\PixTransaction\ObjectValues;

use DateTimeImmutable;

class Timestamp
{
    private DateTimeImmutable $value;

    public function __construct(?DateTimeImmutable $dateTime = null)
    {
        $this->value = $dateTime ?? new DateTimeImmutable();
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format(DateTimeImmutable::ATOM); // ISO-8601 format
    }

    public static function fromString(string $dateTimeString): self
    {
        return new self(new DateTimeImmutable($dateTimeString));
    }
}

