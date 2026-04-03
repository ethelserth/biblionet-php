<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\Tests\DTOs;

use Ethelserth\Biblionet\DTOs\Company;
use Ethelserth\Biblionet\DTOs\CompanyInfo;
use Ethelserth\Biblionet\DTOs\Contributor;
use Ethelserth\Biblionet\DTOs\Language;
use Ethelserth\Biblionet\DTOs\Person;
use Ethelserth\Biblionet\DTOs\Subject;
use Ethelserth\Biblionet\DTOs\TitleSubject;
use PHPUnit\Framework\TestCase;

final class PersonTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = Person::fromApiResponse([
            'PersonsID'  => '128132',
            'Photo'      => '/wp-content/uploadsPersonImages/13/128132.jpg',
            'Name'       => 'Μαργαρίτα',
            'MiddleName' => '',
            'Surname'    => 'Αλευρίδη',
            'BornYear'   => '1970',
            'DeathYear'  => '',
            'Biography'  => 'Γεννήθηκε το 1970 στην Αθήνα.',
            'LastUpdate' => '2020-10-02',
        ]);

        $this->assertSame(128132, $dto->personsId);
        $this->assertSame('Μαργαρίτα', $dto->name);
        $this->assertSame('Αλευρίδη', $dto->surname);
        $this->assertSame('1970', $dto->bornYear);
        $this->assertSame('Γεννήθηκε το 1970 στην Αθήνα.', $dto->biography);
        $this->assertSame('2020-10-02', $dto->lastUpdate);
        $this->assertSame(
            'https://biblionet.gr/wp-content/uploadsPersonImages/13/128132.jpg',
            $dto->photo
        );
    }

    public function test_empty_strings_become_null(): void
    {
        $dto = Person::fromApiResponse([
            'PersonsID'  => '12035',
            'Photo'      => '',
            'Name'       => 'Γεώργιος',
            'MiddleName' => '',
            'Surname'    => 'Λεοντάρης',
            'BornYear'   => '',
            'DeathYear'  => '',
            'Biography'  => '',
            'LastUpdate' => '2020-10-02',
        ]);

        $this->assertNull($dto->middleName);
        $this->assertNull($dto->bornYear);
        $this->assertNull($dto->deathYear);
        $this->assertNull($dto->biography);
        $this->assertNull($dto->photo);
    }
}
