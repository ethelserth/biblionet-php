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

final class CompanyInfoTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = CompanyInfo::fromApiResponse([
            'ComID'            => '32',
            'Title'            => 'Εκκρεμές',
            'AlternativeTitle' => '',
            'Address'          => "Ιουλιανού 41-43\r\n104 33 Αθήνα\r\n",
            'TelephoneNumner'  => '210 8220006',
            'Email'            => 'ekkremes@ekkremes.gr',
            'Website'          => 'www.ekkremes.gr',
            'LastUpdate'       => null,
        ]);

        $this->assertSame(32, $dto->comId);
        $this->assertSame('Εκκρεμές', $dto->title);
        $this->assertNull($dto->alternativeTitle);
        $this->assertSame('Ιουλιανού 41-43' . "\n" . '104 33 Αθήνα', $dto->address);
        $this->assertSame('210 8220006', $dto->telephoneNumber);
        $this->assertSame('ekkremes@ekkremes.gr', $dto->email);
        $this->assertSame('www.ekkremes.gr', $dto->website);
        $this->assertNull($dto->lastUpdate);
    }
}
