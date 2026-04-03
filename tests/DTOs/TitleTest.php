<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\Tests\DTOs;

use Ethelserth\Biblionet\DTOs\Title;
use PHPUnit\Framework\TestCase;

final class TitleTest extends TestCase
{
    private function payload(): array
    {
        return [
            'TitlesID'              => '72584',
            'CoverImage'            => '/wp-content/uploadsTitleImages/08/b72584.jpg',
            'Title'                 => 'Θεραπείας συνέχεια',
            'Subtitle'              => '',
            'ParallelTitle'         => '',
            'AlternativeTitle'      => '',
            'OriginalTitle'         => '',
            'ISBN'                  => '978-960-211-652-4',
            'ISBN_2'                => '',
            'ISBN_3'                => '',
            'ISMN'                  => '',
            'PublisherID'           => '212',
            'Publisher'             => 'Νεφέλη',
            'WriterID'              => '15521',
            'Writer'                => 'Λοϊζίδη, Νίκη',
            'WriterName'            => 'Νίκη Λοϊζίδη',
            'FirstPublishDate'      => '2002-01-01',
            'CurrentPublishDate'    => '2002-01-01',
            'FuturePublishDate'     => null,
            'Place'                 => 'Αθήνα',
            'TitleType'             => 'Βιβλίο',
            'EditionNo'             => null,
            'Cover'                 => 'Μαλακό εξώφυλλο',
            'Dimensions'            => '17x12',
            'PageNo'                => '119',
            'Availability'          => 'Κυκλοφορεί',
            'Price'                 => '5.8300',
            'VAT'                   => '6',
            'Weight'                => '128',
            'AgeFrom'               => null,
            'AgeTo'                 => null,
            'Summary'               => 'Περίληψη του βιβλίου.',
            'Language'              => '',
            'LanguageOriginal'      => '',
            'LanguageTranslatedFrom'=> '',
            'Contains'              => null,
            'Series'                => 'Σύγχρονη Ελληνική Πεζογραφία',
            'SeriesNo'              => null,
            'SubSeries'             => null,
            'SubSeriesNo'           => null,
            'MultiVolumeTitle'      => null,
            'SetISBN'               => null,
            'VolumeNo'              => null,
            'VolumeCount'           => null,
            'Specifications'        => '',
            'WebAddress'            => null,
            'Comments'              => null,
            'CategoryID'            => '39',
            'Category'              => 'Ελληνική λογοτεχνία',
            'LastUpdate'            => null,
        ];
    }

    public function test_maps_all_fields_correctly(): void
    {
        $dto = Title::fromApiResponse($this->payload());

        $this->assertSame(72584, $dto->titlesId);
        $this->assertSame('Θεραπείας συνέχεια', $dto->title);
        $this->assertSame('978-960-211-652-4', $dto->isbn);
        $this->assertSame(212, $dto->publisherId);
        $this->assertSame('Νεφέλη', $dto->publisher);
        $this->assertSame('2002-01-01', $dto->firstPublishDate);
        $this->assertSame('2002-01-01', $dto->currentPublishDate);
        $this->assertSame('Μαλακό εξώφυλλο', $dto->cover);
        $this->assertSame('17x12', $dto->dimensions);
        $this->assertSame(119, $dto->pageNo);
        $this->assertSame('5.8300', $dto->price);
        $this->assertSame(6, $dto->vat);
        $this->assertSame(128, $dto->weight);
        $this->assertSame('Περίληψη του βιβλίου.', $dto->summary);
        $this->assertSame('Σύγχρονη Ελληνική Πεζογραφία', $dto->series);
    }

    public function test_null_api_fields_remain_null(): void
    {
        $dto = Title::fromApiResponse($this->payload());

        $this->assertNull($dto->futurePublishDate);
        $this->assertNull($dto->editionNo);
        $this->assertNull($dto->ageFrom);
        $this->assertNull($dto->ageTo);
        $this->assertNull($dto->contains);
        $this->assertNull($dto->seriesNo);
        $this->assertNull($dto->lastUpdate);
    }

    public function test_empty_strings_become_null(): void
    {
        $dto = Title::fromApiResponse($this->payload());

        $this->assertNull($dto->subtitle);
        $this->assertNull($dto->language);
        $this->assertNull($dto->languageOriginal);
        $this->assertNull($dto->languageTranslatedFrom);
        $this->assertNull($dto->specifications);
    }

    public function test_physical_fields_cast_to_int(): void
    {
        $dto = Title::fromApiResponse($this->payload());

        $this->assertIsInt($dto->pageNo);
        $this->assertIsInt($dto->vat);
        $this->assertIsInt($dto->weight);
    }

    public function test_cover_image_gets_base_url_prepended(): void
    {
        $dto = Title::fromApiResponse($this->payload());

        $this->assertSame(
            'https://biblionet.gr/wp-content/uploadsTitleImages/08/b72584.jpg',
            $dto->coverImage
        );
    }
}