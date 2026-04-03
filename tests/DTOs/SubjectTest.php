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

final class SubjectTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = Subject::fromApiResponse([
            'SubjectsID'    => '72',
            'SubjectTitle'  => 'Λαϊκό θέατρο - Ελλάς',
            'SubjectDDC'    => '792.495',
            'SubjectParent' => '1125',
        ]);

        $this->assertSame(72, $dto->subjectsId);
        $this->assertSame('Λαϊκό θέατρο - Ελλάς', $dto->subjectTitle);
        $this->assertSame('792.495', $dto->subjectDdc);
        $this->assertSame(1125, $dto->subjectParent);
    }

    public function test_null_parent_for_root_subjects(): void
    {
        $dto = Subject::fromApiResponse([
            'SubjectsID'    => '1',
            'SubjectTitle'  => 'Ριζικό θέμα',
            'SubjectDDC'    => '000',
            'SubjectParent' => '',
        ]);

        $this->assertNull($dto->subjectParent);
    }
}