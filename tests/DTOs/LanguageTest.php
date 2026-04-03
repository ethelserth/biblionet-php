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

final class LanguageTest extends TestCase
{
    public function test_maps_all_fields_correctly(): void
    {
        $dto = Language::fromApiResponse([
            'LangsID'  => '7',
            'Language' => 'ισπανικά',
        ]);

        $this->assertSame(7, $dto->langsId);
        $this->assertSame('ισπανικά', $dto->language);
    }
}