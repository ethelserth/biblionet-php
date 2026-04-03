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

final class TitleSubjectTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = TitleSubject::fromApiResponse([
            'TitlesID'    => '72584',
            'Titles'      => 'Θεραπείας συνέχεια',
            'SubjectsID'  => '20',
            'SubjectTitle'=> 'Νεοελληνική πεζογραφία - Προσωπικές αφηγήσεις',
            'SubjectDDC'  => '889.3',
            'SubjectOrder'=> '1',
        ]);

        $this->assertSame(72584, $dto->titlesId);
        $this->assertSame('Θεραπείας συνέχεια', $dto->title);
        $this->assertSame(20, $dto->subjectsId);
        $this->assertSame('Νεοελληνική πεζογραφία - Προσωπικές αφηγήσεις', $dto->subjectTitle);
        $this->assertSame('889.3', $dto->subjectDdc);
        $this->assertSame(1, $dto->subjectOrder);
    }
}
