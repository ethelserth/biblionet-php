<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class TitleSubject
{
    public function __construct(
        public readonly int $titlesId,
        public readonly string $title,
        public readonly int $subjectsId,
        public readonly string $subjectTitle,
        public readonly string $subjectDdc,
        public readonly int $subjectOrder,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            titlesId: (int) $data['TitlesID'],
            title: $data['Titles'],
            subjectsId: (int) $data['SubjectsID'],
            subjectTitle: $data['SubjectTitle'],
            subjectDdc: $data['SubjectDDC'],
            subjectOrder: (int) $data['SubjectOrder'],
        );
    }
}