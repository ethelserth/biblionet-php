<?php

declare(strict_types=1);

namespace Ethelserth\Biblionet;

use Ethelserth\Biblionet\DTOs\CompanyInfo;
use Ethelserth\Biblionet\DTOs\Contributor;
use Ethelserth\Biblionet\DTOs\Company;
use Ethelserth\Biblionet\DTOs\Language;
use Ethelserth\Biblionet\DTOs\Person;
use Ethelserth\Biblionet\DTOs\Subject;
use Ethelserth\Biblionet\DTOs\Title;
use Ethelserth\Biblionet\DTOs\TitleSubject;
use Ethelserth\Biblionet\DTOs\TitleSummary;
use Ethelserth\Biblionet\Exceptions\BiblionetException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class BiblionetClient
{
    private const BASE_URL = 'https://biblionet.gr/webservice/';

    public function __construct(
        private readonly ClientInterface $httpClient,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly StreamFactoryInterface $streamFactory,
        private readonly string $username,
        private readonly string $password,
    ) {}

    /**
     * Fetch a single full title record by its Biblionet ID.
     */
    public function getTitle(int $id): Title
    {
        $data = $this->post('get_title', ['title' => $id]);

        // get_title returns the same double-nested [[{...}]] envelope as all
        // other single-item endpoints (consistent with getSubject/getLanguage).
        return Title::fromApiResponse($data[0][0]);
    }

    /**
     * Fetch a single full title record by ISBN.
     * Dashes in the ISBN are ignored by the API.
     */
    public function getTitleByIsbn(string $isbn): Title
    {
        $data = $this->post('get_title', ['isbn' => $isbn]);

        return Title::fromApiResponse($data[0][0]);
    }

    /**
     * Fetch all full title records updated or created on a specific date.
     * Date format: YYYY-MM-DD.
     *
     * @return Title[]
     */
    public function getTitlesByLastUpdate(string $date): array
    {
        $data = $this->post('get_title', ['lastupdate' => $date]);

        // When called with lastupdate, the API returns the double-nested format.
        return array_map(
            fn(array $item) => Title::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch paginated titles for a given month.
     * The API returns full title records — same structure as get_title.
     *
     * @return Title[]
     */
    public function getMonthTitles(
        int $year,
        int $month,
        int $page = 1,
        int $perPage = 50,
    ): array {
        $data = $this->post('get_month_titles', [
            'year'            => $year,
            'month'           => $month,
            'page'            => $page,
            'titles_per_page' => $perPage,
        ]);

        return array_map(
            fn(array $item) => Title::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch all contributors for a given title ID.
     *
     * @return Contributor[]
     */
    public function getContributors(int $titleId): array
    {
        $data = $this->post('get_contributors', ['title' => $titleId]);

        return array_map(
            fn(array $item) => Contributor::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch all companies (publishers, distributors etc.) for a given title ID.
     *
     * @return Company[]
     */
    public function getTitleCompanies(int $titleId): array
    {
        $data = $this->post('get_title_companies', ['title' => $titleId]);

        return array_map(
            fn(array $item) => Company::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch all DDC subjects assigned to a given title ID.
     *
     * @return TitleSubject[]
     */
    public function getTitleSubjects(int $titleId): array
    {
        $data = $this->post('get_title_subject', ['title' => $titleId]);

        return array_map(
            fn(array $item) => TitleSubject::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch a person by their Biblionet person ID,
     * or fetch all persons updated on a specific date (YYYY-MM-DD).
     *
     * @return Person[]
     */
    public function getPersons(int $personId): array
    {
        $data = $this->post('get_person', ['person' => $personId]);

        return array_map(
            fn(array $item) => Person::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch all persons updated or created on a specific date.
     * Date format: YYYY-MM-DD.
     *
     * @return Person[]
     */
    public function getPersonsByLastUpdate(string $date): array
    {
        $data = $this->post('get_person', ['lastupdate' => $date]);

        return array_map(
            fn(array $item) => Person::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch full company information by company ID.
     *
     * @return CompanyInfo[]
     */
    public function getCompany(int $companyId): array
    {
        $data = $this->post('get_company', ['company' => $companyId]);

        return array_map(
            fn(array $item) => CompanyInfo::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch all companies updated or created on a specific date.
     * Date format: YYYY-MM-DD.
     *
     * @return CompanyInfo[]
     */
    public function getCompaniesByLastUpdate(string $date): array
    {
        $data = $this->post('get_company', ['lastupdate' => $date]);

        return array_map(
            fn(array $item) => CompanyInfo::fromApiResponse($item),
            $data[0]
        );
    }

    /**
     * Fetch a subject by its Biblionet subject ID.
     */
    public function getSubject(int $subjectId): Subject
    {
        $data = $this->post('get_subject', ['subject' => $subjectId]);

        return Subject::fromApiResponse($data[0][0]);
    }

    /**
     * Fetch a language by its Biblionet language ID.
     */
    public function getLanguage(int $languageId): Language
    {
        $data = $this->post('get_language', ['language' => $languageId]);

        return Language::fromApiResponse($data[0][0]);
    }

    /**
     * Build and send a POST request to a Biblionet endpoint.
     * Credentials are merged automatically into every request.
     * Returns the decoded response — callers are responsible for unwrapping
     * the nesting appropriate to their endpoint.
     */
    private function post(string $endpoint, array $params = []): array
    {
        $body = http_build_query(array_merge([
            'username' => $this->username,
            'password' => $this->password,
        ], $params));

        $request = $this->requestFactory
            ->createRequest('POST', self::BASE_URL . $endpoint)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody($this->streamFactory->createStream($body));

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new BiblionetException(
                message: 'HTTP request to Biblionet API failed: ' . $e->getMessage(),
                previous: $e,
            );
        }

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200) {
            throw new BiblionetException(
                "Biblionet API returned unexpected status code {$statusCode}."
            );
        }

        $decoded = json_decode(
            json: (string) $response->getBody(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );

        if (!is_array($decoded) || empty($decoded)) {
            throw new BiblionetException('Biblionet API returned an empty or malformed response.');
        }

        if (isset($decoded['error'])) {
            throw new BiblionetException('Biblionet API error: ' . $decoded['error']['error']);
        }

        return $decoded;
    }
}