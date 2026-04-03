<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class Language
{
    public function __construct(
        public readonly int $langsId,
        public readonly string $language,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            langsId: (int) $data['LangsID'],
            language: $data['Language'],
        );
    }
}