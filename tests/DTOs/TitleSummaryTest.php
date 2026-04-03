<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\Tests\DTOs;

use Ethelserth\Biblionet\DTOs\TitleSummary;
use PHPUnit\Framework\TestCase;

final class TitleSummaryTest extends TestCase
{
    private function payload(): array
    {
        return [
            'TitlesID'        => '250752',
            'CoverImage'      => '/wp-content/uploadsTitleImages/26/b250752.jpg',
            'Title'           => 'Στέφανος Αβακιάν',
            'Subtitle'        => 'Εγώ και τα παιδιά που μου έδωσες',
            'ParallelTitle'   => '',
            'AlternativeTitle'=> '',
            'OriginalTitle'   => '',
            'ISBN'            => '618-5376-05-5',
            'ISBN_2'          => '',
            'ISBN_3'          => '',
            'ISMN'            => '',
            'PublisherID'     => '6342',
            'Publisher'       => 'Μάτι',
            'WriterID'        => '129690',
            'Writer'          => 'Αβακιάν, Στέφανος',
            'WriterName'      => 'Στέφανος Αβακιάν',
            'Place'           => 'Κατερίνη',
            'PublishYear'     => '2019',
            'PublishMonth'    => '2',
            'TitleType'       => 'Βιβλίο',
            'Availability'    => 'Κυκλοφορεί - Εκκρεμής εγγραφή',
            'CategoryID'      => '39',
            'Category'        => 'Ελληνική λογοτεχνία',
            'LastUpdate'      => '2020-10-17',
        ];
    }

    public function test_maps_all_fields_correctly(): void
    {
        $dto = TitleSummary::fromApiResponse($this->payload());

        $this->assertSame(250752, $dto->titlesId);
        $this->assertSame('Στέφανος Αβακιάν', $dto->title);
        $this->assertSame('Εγώ και τα παιδιά που μου έδωσες', $dto->subtitle);
        $this->assertSame('618-5376-05-5', $dto->isbn);
        $this->assertSame(6342, $dto->publisherId);
        $this->assertSame('Μάτι', $dto->publisher);
        $this->assertSame(129690, $dto->writerId);
        $this->assertSame('Αβακιάν, Στέφανος', $dto->writer);
        $this->assertSame('Στέφανος Αβακιάν', $dto->writerName);
        $this->assertSame('Κατερίνη', $dto->place);
        $this->assertSame(2019, $dto->publishYear);
        $this->assertSame(2, $dto->publishMonth);
        $this->assertSame('Βιβλίο', $dto->titleType);
        $this->assertSame(39, $dto->categoryId);
        $this->assertSame('Ελληνική λογοτεχνία', $dto->category);
        $this->assertSame('2020-10-17', $dto->lastUpdate);
    }

    public function test_empty_strings_become_null(): void
    {
        $dto = TitleSummary::fromApiResponse($this->payload());

        $this->assertNull($dto->parallelTitle);
        $this->assertNull($dto->alternativeTitle);
        $this->assertNull($dto->originalTitle);
        $this->assertNull($dto->isbn2);
        $this->assertNull($dto->isbn3);
        $this->assertNull($dto->ismn);
    }

    public function test_cover_image_gets_base_url_prepended(): void
    {
        $dto = TitleSummary::fromApiResponse($this->payload());

        $this->assertSame(
            'https://biblionet.gr/wp-content/uploadsTitleImages/26/b250752.jpg',
            $dto->coverImage
        );
    }

    public function test_empty_cover_image_becomes_null(): void
    {
        $payload = $this->payload();
        $payload['CoverImage'] = '';

        $dto = TitleSummary::fromApiResponse($payload);

        $this->assertNull($dto->coverImage);
    }

    public function test_ids_are_cast_to_integers(): void
    {
        $dto = TitleSummary::fromApiResponse($this->payload());

        $this->assertIsInt($dto->titlesId);
        $this->assertIsInt($dto->publisherId);
        $this->assertIsInt($dto->writerId);
        $this->assertIsInt($dto->categoryId);
        $this->assertIsInt($dto->publishYear);
        $this->assertIsInt($dto->publishMonth);
    }
}