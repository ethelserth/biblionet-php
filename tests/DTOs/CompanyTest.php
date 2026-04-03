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


final class CompanyTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = Company::fromApiResponse([
            'TitlesID'     => '250001',
            'Title'        => 'Ιερά ασματική ακολουθία',
            'CompanyID'    => '7123',
            'CompanyName'  => 'Ιερά Μεγίστη Μονή Βατοπαιδίου',
            'ComKindID'    => '1',
            'ComKindType'  => 'Εκδότης',
            'PresentOrder' => '1',
        ]);

        $this->assertSame(250001, $dto->titlesId);
        $this->assertSame(7123, $dto->companyId);
        $this->assertSame('Ιερά Μεγίστη Μονή Βατοπαιδίου', $dto->companyName);
        $this->assertSame(1, $dto->comKindId);
        $this->assertSame('Εκδότης', $dto->comKindType);
        $this->assertSame(1, $dto->presentOrder);
    }
}
