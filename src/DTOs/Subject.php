<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class Subject
{
    public function __construct(
        public readonly int $subjectsId,
        public readonly string $subjectTitle,
        public readonly string $subjectDdc,
        public readonly ?int $subjectParent,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            subjectsId: (int) $data['SubjectsID'],
            subjectTitle: $data['SubjectTitle'],
            subjectDdc: $data['SubjectDDC'],
            subjectParent: isset($data['SubjectParent']) && $data['SubjectParent'] !== '' ? (int) $data['SubjectParent'] : null,
        );
    }
}