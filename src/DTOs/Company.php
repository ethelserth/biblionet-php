<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class Company
{
    public function __construct(
        public readonly int $titlesId,
        public readonly string $title,
        public readonly int $companyId,
        public readonly string $companyName,
        public readonly int $comKindId,
        public readonly string $comKindType,
        public readonly int $presentOrder,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            titlesId: (int) $data['TitlesID'],
            title: $data['Title'],
            companyId: (int) $data['CompanyID'],
            companyName: $data['CompanyName'],
            comKindId: (int) $data['ComKindID'],
            comKindType: $data['ComKindType'],
            presentOrder: (int) $data['PresentOrder'],
        );
    }
}