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

final class ContributorTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = Contributor::fromApiResponse([
            'TitlesID'           => '89',
            'Title'              => 'Μπλε πάγος',
            'ContributorID'      => '958',
            'ContributorFullName'=> 'Pino Corrias',
            'ContributorTypeID'  => '1',
            'ContributorType'    => 'Συγγραφέας',
            'PresentOrder'       => '1',
        ]);

        $this->assertSame(89, $dto->titlesId);
        $this->assertSame('Μπλε πάγος', $dto->title);
        $this->assertSame(958, $dto->contributorId);
        $this->assertSame('Pino Corrias', $dto->contributorFullName);
        $this->assertSame(1, $dto->contributorTypeId);
        $this->assertSame('Συγγραφέας', $dto->contributorType);
        $this->assertSame(1, $dto->presentOrder);
    }

    public function test_all_ids_are_integers(): void
    {
        $dto = Contributor::fromApiResponse([
            'TitlesID'           => '89',
            'Title'              => 'Μπλε πάγος',
            'ContributorID'      => '958',
            'ContributorFullName'=> 'Pino Corrias',
            'ContributorTypeID'  => '1',
            'ContributorType'    => 'Συγγραφέας',
            'PresentOrder'       => '1',
        ]);

        $this->assertIsInt($dto->titlesId);
        $this->assertIsInt($dto->contributorId);
        $this->assertIsInt($dto->contributorTypeId);
        $this->assertIsInt($dto->presentOrder);
    }
}
