<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class Person
{
    public function __construct(
        public readonly int $personsId,
        public readonly string $name,
        public readonly ?string $middleName,
        public readonly string $surname,
        public readonly ?string $bornYear,
        public readonly ?string $deathYear,
        public readonly ?string $biography,
        public readonly ?string $photo,
        public readonly ?string $lastUpdate,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            personsId: (int) $data['PersonsID'],
            name: $data['Name'],
            middleName: $data['MiddleName'] !== '' ? $data['MiddleName'] : null,
            surname: $data['Surname'],
            bornYear: $data['BornYear'] !== '' ? $data['BornYear'] : null,
            deathYear: $data['DeathYear'] !== '' ? $data['DeathYear'] : null,
            biography: $data['Biography'] !== '' ? $data['Biography'] : null,
            photo: $data['Photo'] !== ''
                ? 'https://biblionet.gr' . $data['Photo']
                : null,
            lastUpdate: $data['LastUpdate'] ?? null,
        );
    }
}