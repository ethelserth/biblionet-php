<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class CompanyInfo
{
    public function __construct(
        public readonly int $comId,
        public readonly string $title,
        public readonly ?string $alternativeTitle,
        public readonly ?string $address,
        public readonly ?string $telephoneNumber,
        public readonly ?string $email,
        public readonly ?string $website,
        public readonly ?string $lastUpdate,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            comId: (int) $data['ComID'],
            title: $data['Title'],
            alternativeTitle: $data['AlternativeTitle'] !== '' ? $data['AlternativeTitle'] : null,
            address: isset($data['Address']) && $data['Address'] !== '' ? trim(str_replace("\r\n", "\n", $data['Address'])) : null,
            telephoneNumber: isset($data['TelephoneNumner']) && $data['TelephoneNumner'] !== '' ? $data['TelephoneNumner'] : null,
            email: isset($data['Email']) && $data['Email'] !== '' ? $data['Email'] : null,
            website: isset($data['Website']) && $data['Website'] !== '' ? $data['Website'] : null,
            lastUpdate: $data['LastUpdate'] ?? null,
        );
    }
}