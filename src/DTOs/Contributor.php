<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class Contributor
{
    public function __construct(
        public readonly int $titlesId,
        public readonly string $title,
        public readonly int $contributorId,
        public readonly string $contributorFullName,
        public readonly int $contributorTypeId,
        public readonly string $contributorType,
        public readonly int $presentOrder,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            titlesId: (int) $data['TitlesID'],
            title: $data['Title'],
            contributorId: (int) $data['ContributorID'],
            contributorFullName: $data['ContributorFullName'],
            contributorTypeId: (int) $data['ContributorTypeID'],
            contributorType: $data['ContributorType'],
            presentOrder: (int) $data['PresentOrder'],
        );
    }
}