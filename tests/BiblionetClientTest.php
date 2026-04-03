<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet\Tests;

use Ethelserth\Biblionet\BiblionetClient;
use Ethelserth\Biblionet\DTOs\CompanyInfo;
use Ethelserth\Biblionet\DTOs\Contributor;
use Ethelserth\Biblionet\DTOs\Language;
use Ethelserth\Biblionet\DTOs\Person;
use Ethelserth\Biblionet\DTOs\Subject;
use Ethelserth\Biblionet\DTOs\Title;
use Ethelserth\Biblionet\DTOs\TitleSubject;
use Ethelserth\Biblionet\DTOs\TitleSummary;
use Ethelserth\Biblionet\Exceptions\BiblionetException;
use Ethelserth\Biblionet\Tests\Support\MockHttpClient;
use GuzzleHttp\Psr7\HttpFactory;
use PHPUnit\Framework\TestCase;

final class BiblionetClientTest extends TestCase
{
    private function makeClient(int $status, mixed $body): BiblionetClient
    {
        $factory = new HttpFactory();

        return new BiblionetClient(
            httpClient: MockHttpClient::respondWith($status, $body),
            requestFactory: $factory,
            streamFactory: $factory,
            username: 'testuser',
            password: 'testpsw',
        );
    }

    // -------------------------------------------------------------------------
    // getTitle
    // -------------------------------------------------------------------------

    public function test_get_title_returns_title_dto(): void
    {
        $client = $this->makeClient(200, $this->titlePayload());

        $title = $client->getTitle(72584);

        $this->assertInstanceOf(Title::class, $title);
        $this->assertSame(72584, $title->titlesId);
        $this->assertSame('Θεραπείας συνέχεια', $title->title);
    }

    // -------------------------------------------------------------------------
    // getMonthTitles
    // -------------------------------------------------------------------------

    public function test_get_month_titles_returns_array_of_title_summaries(): void
    {
        $client = $this->makeClient(200, [[$this->titleSummaryPayload(), $this->titleSummaryPayload()]]);

        $summaries = $client->getMonthTitles(2024, 1);

        $this->assertCount(2, $summaries);
        $this->assertContainsOnlyInstancesOf(TitleSummary::class, $summaries);
    }

    // -------------------------------------------------------------------------
    // getTitlesByLastUpdate
    // -------------------------------------------------------------------------

    public function test_get_titles_by_last_update_returns_array_of_titles(): void
    {
        $client = $this->makeClient(200, [[$this->titlePayload(), $this->titlePayload()]]);

        $titles = $client->getTitlesByLastUpdate('2024-01-15');

        $this->assertCount(2, $titles);
        $this->assertContainsOnlyInstancesOf(Title::class, $titles);
    }

    // -------------------------------------------------------------------------
    // getContributors
    // -------------------------------------------------------------------------

    public function test_get_contributors_returns_array_of_contributors(): void
    {
        $client = $this->makeClient(200, [[$this->contributorPayload()]]);

        $contributors = $client->getContributors(89);

        $this->assertCount(1, $contributors);
        $this->assertInstanceOf(Contributor::class, $contributors[0]);
        $this->assertSame('Pino Corrias', $contributors[0]->contributorFullName);
    }

    // -------------------------------------------------------------------------
    // getTitleSubjects
    // -------------------------------------------------------------------------

    public function test_get_title_subjects_returns_array_of_title_subjects(): void
    {
        $client = $this->makeClient(200, [[$this->titleSubjectPayload()]]);

        $subjects = $client->getTitleSubjects(72584);

        $this->assertCount(1, $subjects);
        $this->assertInstanceOf(TitleSubject::class, $subjects[0]);
        $this->assertSame('889.3', $subjects[0]->subjectDdc);
    }

    // -------------------------------------------------------------------------
    // getPersons
    // -------------------------------------------------------------------------

    public function test_get_persons_returns_array_of_persons(): void
    {
        $client = $this->makeClient(200, [[$this->personPayload()]]);

        $persons = $client->getPersons(128132);

        $this->assertCount(1, $persons);
        $this->assertInstanceOf(Person::class, $persons[0]);
        $this->assertSame('Μαργαρίτα', $persons[0]->name);
    }

    // -------------------------------------------------------------------------
    // getCompany
    // -------------------------------------------------------------------------

    public function test_get_company_returns_array_of_company_info(): void
    {
        $client = $this->makeClient(200, [[$this->companyInfoPayload()]]);

        $companies = $client->getCompany(32);

        $this->assertCount(1, $companies);
        $this->assertInstanceOf(CompanyInfo::class, $companies[0]);
        $this->assertSame('Εκκρεμές', $companies[0]->title);
    }

    // -------------------------------------------------------------------------
    // getSubject
    // -------------------------------------------------------------------------

    public function test_get_subject_returns_subject_dto(): void
    {
        $client = $this->makeClient(200, [[$this->subjectPayload()]]);

        $subject = $client->getSubject(72);

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertSame(72, $subject->subjectsId);
        $this->assertSame('792.495', $subject->subjectDdc);
    }

    // -------------------------------------------------------------------------
    // getLanguage
    // -------------------------------------------------------------------------

    public function test_get_language_returns_language_dto(): void
    {
        $client = $this->makeClient(200, [[['LangsID' => '7', 'Language' => 'ισπανικά']]]);

        $language = $client->getLanguage(7);

        $this->assertInstanceOf(Language::class, $language);
        $this->assertSame(7, $language->langsId);
        $this->assertSame('ισπανικά', $language->language);
    }

    // -------------------------------------------------------------------------
    // Error handling
    // -------------------------------------------------------------------------

    public function test_non_200_response_throws_exception(): void
    {
        $this->expectException(BiblionetException::class);
        $this->expectExceptionMessageMatches('/status code 500/');

        $client = $this->makeClient(500, '');

        $client->getTitle(1);
    }

    public function test_api_error_response_throws_exception(): void
    {
        $this->expectException(BiblionetException::class);
        $this->expectExceptionMessageMatches('/Biblionet API error/');

        $client = $this->makeClient(200, ['error' => ['error' => 'Μη έγκυρα στοιχεία σύνδεσης']]);

        $client->getTitle(1);
    }

    public function test_malformed_json_throws_exception(): void
    {
        $this->expectException(\Throwable::class);

        $client = $this->makeClient(200, 'not-valid-json{{{{');

        $client->getTitle(1);
    }

    public function test_empty_response_throws_exception(): void
    {
        $this->expectException(BiblionetException::class);
        $this->expectExceptionMessageMatches('/empty or malformed/');

        $client = $this->makeClient(200, []);

        $client->getTitle(1);
    }

    // -------------------------------------------------------------------------
    // Fixture payloads
    // -------------------------------------------------------------------------

    private function titlePayload(): array
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
            'Summary'               => 'Περίληψη.',
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

    private function titleSummaryPayload(): array
    {
        return [
            'TitlesID'         => '250752',
            'CoverImage'       => '/wp-content/uploadsTitleImages/26/b250752.jpg',
            'Title'            => 'Στέφανος Αβακιάν',
            'Subtitle'         => '',
            'ParallelTitle'    => '',
            'AlternativeTitle' => '',
            'OriginalTitle'    => '',
            'ISBN'             => '618-5376-05-5',
            'ISBN_2'           => '',
            'ISBN_3'           => '',
            'ISMN'             => '',
            'PublisherID'      => '6342',
            'Publisher'        => 'Μάτι',
            'WriterID'         => '129690',
            'Writer'           => 'Αβακιάν, Στέφανος',
            'WriterName'       => 'Στέφανος Αβακιάν',
            'Place'            => 'Κατερίνη',
            'PublishYear'      => '2019',
            'PublishMonth'     => '2',
            'TitleType'        => 'Βιβλίο',
            'Availability'     => 'Κυκλοφορεί',
            'CategoryID'       => '39',
            'Category'         => 'Ελληνική λογοτεχνία',
            'LastUpdate'       => '2020-10-17',
        ];
    }

    private function contributorPayload(): array
    {
        return [
            'TitlesID'            => '89',
            'Title'               => 'Μπλε πάγος',
            'ContributorID'       => '958',
            'ContributorFullName' => 'Pino Corrias',
            'ContributorTypeID'   => '1',
            'ContributorType'     => 'Συγγραφέας',
            'PresentOrder'        => '1',
        ];
    }

    private function titleSubjectPayload(): array
    {
        return [
            'TitlesID'     => '72584',
            'Titles'       => 'Θεραπείας συνέχεια',
            'SubjectsID'   => '20',
            'SubjectTitle' => 'Νεοελληνική πεζογραφία - Προσωπικές αφηγήσεις',
            'SubjectDDC'   => '889.3',
            'SubjectOrder' => '1',
        ];
    }

    private function personPayload(): array
    {
        return [
            'PersonsID'  => '128132',
            'Photo'      => '/wp-content/uploadsPersonImages/13/128132.jpg',
            'Name'       => 'Μαργαρίτα',
            'MiddleName' => '',
            'Surname'    => 'Αλευρίδη',
            'BornYear'   => '',
            'DeathYear'  => '',
            'Biography'  => 'Γεννήθηκε το 1970 στην Αθήνα.',
            'LastUpdate' => '2020-10-02',
        ];
    }

    private function companyInfoPayload(): array
    {
        return [
            'ComID'            => '32',
            'Title'            => 'Εκκρεμές',
            'AlternativeTitle' => '',
            'Address'          => "Ιουλιανού 41-43\r\n104 33 Αθήνα\r\n",
            'TelephoneNumner'  => '210 8220006',
            'Email'            => 'ekkremes@ekkremes.gr',
            'Website'          => 'www.ekkremes.gr',
            'LastUpdate'       => null,
        ];
    }

    private function subjectPayload(): array
    {
        return [
            'SubjectsID'    => '72',
            'SubjectTitle'  => 'Λαϊκό θέατρο - Ελλάς',
            'SubjectDDC'    => '792.495',
            'SubjectParent' => '1125',
        ];
    }
}