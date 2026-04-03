<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\DTOs;

final class TitleSummary
{
    public function __construct(
        public readonly int $titlesId,
        public readonly string $title,
        public readonly ?string $subtitle,
        public readonly ?string $parallelTitle,
        public readonly ?string $alternativeTitle,
        public readonly ?string $originalTitle,
        public readonly ?string $isbn,
        public readonly ?string $isbn2,
        public readonly ?string $isbn3,
        public readonly ?string $ismn,
        public readonly int $publisherId,
        public readonly string $publisher,
        public readonly int $writerId,
        public readonly string $writer,
        public readonly string $writerName,
        public readonly ?string $place,
        public readonly int $publishYear,
        public readonly int $publishMonth,
        public readonly string $titleType,
        public readonly string $availability,
        public readonly int $categoryId,
        public readonly string $category,
        public readonly ?string $coverImage,
        public readonly ?string $lastUpdate,
    ) {}

    public static function fromApiResponse(array $data): self
    {
        return new self(
            titlesId: (int) $data['TitlesID'],
            title: $data['Title'],
            subtitle: $data['Subtitle'] !== '' ? $data['Subtitle'] : null,
            parallelTitle: $data['ParallelTitle'] !== '' ? $data['ParallelTitle'] : null,
            alternativeTitle: $data['AlternativeTitle'] !== '' ? $data['AlternativeTitle'] : null,
            originalTitle: $data['OriginalTitle'] !== '' ? $data['OriginalTitle'] : null,
            isbn: $data['ISBN'] !== '' ? $data['ISBN'] : null,
            isbn2: $data['ISBN_2'] !== '' ? $data['ISBN_2'] : null,
            isbn3: $data['ISBN_3'] !== '' ? $data['ISBN_3'] : null,
            ismn: $data['ISMN'] !== '' ? $data['ISMN'] : null,
            publisherId: (int) $data['PublisherID'],
            publisher: $data['Publisher'],
            writerId: (int) $data['WriterID'],
            writer: $data['Writer'],
            writerName: $data['WriterName'],
            place: $data['Place'] !== '' ? $data['Place'] : null,
            publishYear: (int) $data['PublishYear'],
            publishMonth: (int) $data['PublishMonth'],
            titleType: $data['TitleType'],
            availability: $data['Availability'],
            categoryId: (int) $data['CategoryID'],
            category: $data['Category'],
            coverImage: $data['CoverImage'] !== ''
                ? 'https://biblionet.gr' . $data['CoverImage']
                : null,
            lastUpdate: $data['LastUpdate'] !== '' ? $data['LastUpdate'] : null,
        );
    }
}