# ethelserth/biblionet-php

A framework-agnostic PHP client for the [Biblionet](https://biblionet.gr) Greek books API.

Biblionet is the primary books-in-print database for Greece, covering over 200,000 titles with full bibliographic data, contributor information, subject classification, and commercial details.

This package provides a clean, typed interface to the Biblionet web service. It has no framework dependency and works with any PSR-18 compatible HTTP client.

If you are using Laravel, see [ethelserth/biblionet-laravel](https://github.com/ethelserth/biblionet-laravel) for a ready-made service provider and configuration.

---

## Requirements

- PHP 8.1 or higher
- A PSR-18 HTTP client (e.g. `guzzlehttp/guzzle`)
- A PSR-17 HTTP factory (e.g. `guzzlehttp/psr7`)
- Biblionet API credentials (request access at [biblionet.gr](https://biblionet.gr))

---

## Installation

```bash
composer require ethelserth/biblionet-php
```

You will also need a PSR-18 client and PSR-17 factories. If you do not already have one:

```bash
composer require guzzlehttp/guzzle guzzlehttp/psr7
```

---

## Usage

### Instantiation

```php
use Ethelserth\Biblionet\BiblionetClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

$factory = new HttpFactory();

$client = new BiblionetClient(
    httpClient: new Client(),
    requestFactory: $factory,
    streamFactory: $factory,
    username: 'your-username',
    password: 'your-password',
);
```

### Fetching titles

```php
// Full title record by Biblionet ID
$title = $client->getTitle(72584);

echo $title->title;          // "Θεραπείας συνέχεια"
echo $title->publisher;      // "Νεφέλη"
echo $title->price;          // "5.8300"
echo $title->summary;        // Full description text
echo $title->coverImage;     // "https://biblionet.gr/wp-content/..."

// By ISBN (dashes are ignored by the API)
$title = $client->getTitleByIsbn('978-960-211-652-4');

// All titles updated on a specific date
$titles = $client->getTitlesByLastUpdate('2024-01-15');

// Paginated month listing (lighter response, no physical details)
$summaries = $client->getMonthTitles(year: 2024, month: 1, page: 1, perPage: 50);

foreach ($summaries as $summary) {
    echo $summary->title;
    echo $summary->publishYear;
}
```

### Fetching related data

```php
// Contributors for a title (authors, translators, editors etc.)
$contributors = $client->getContributors(72584);

foreach ($contributors as $contributor) {
    echo $contributor->contributorFullName; // "Νίκη Λοϊζίδη"
    echo $contributor->contributorType;     // "Συγγραφέας"
}

// Companies associated with a title (publisher, distributor etc.)
$companies = $client->getTitleCompanies(72584);

// DDC subjects assigned to a title
$subjects = $client->getTitleSubjects(72584);

foreach ($subjects as $subject) {
    echo $subject->subjectTitle; // "Νεοελληνική πεζογραφία - Προσωπικές αφηγήσεις"
    echo $subject->subjectDdc;   // "889.3"
}
```

### Fetching persons and companies

```php
// Full person record by ID
$persons = $client->getPersons(15521);

// All persons updated on a specific date
$persons = $client->getPersonsByLastUpdate('2024-01-15');

// Full company record by ID
$companies = $client->getCompany(212);

// All companies updated on a specific date
$companies = $client->getCompaniesByLastUpdate('2024-01-15');
```

### Fetching subjects and languages

```php
// Subject with DDC code and parent ID for hierarchy traversal
$subject = $client->getSubject(20);

echo $subject->subjectTitle;  // "Νεοελληνική πεζογραφία - Προσωπικές αφηγήσεις"
echo $subject->subjectDdc;    // "889.3"
echo $subject->subjectParent; // parent subject ID (int) or null

// Language by ID
$language = $client->getLanguage(7);

echo $language->language; // "ισπανικά"
```

---

## DTOs

All responses are returned as immutable typed value objects. No business logic lives in the DTOs — they carry exactly what the API returns, with proper PHP types and empty strings converted to `null`.

| DTO | Source endpoint | Notes |
|---|---|---|
| `Title` | `get_title` | Full bibliographic record |
| `TitleSummary` | `get_month_titles` | Lighter listing record |
| `Contributor` | `get_contributors` | Title-contributor relationship |
| `Company` | `get_title_companies` | Title-company relationship |
| `TitleSubject` | `get_title_subject` | Title-subject relationship with DDC |
| `Person` | `get_person` | Full person record |
| `CompanyInfo` | `get_company` | Full company record |
| `Subject` | `get_subject` | Subject with DDC and parent ID |
| `Language` | `get_language` | Language name by ID |

---

## Error handling

All errors throw `Ethelserth\Biblionet\Exceptions\BiblionetException`:

```php
use Ethelserth\Biblionet\Exceptions\BiblionetException;

try {
    $title = $client->getTitle(99999999);
} catch (BiblionetException $e) {
    // API error, HTTP failure, or malformed response
    echo $e->getMessage();
}
```

---

## Testing credentials

Biblionet provides public test credentials for development:

- Username: `testuser`
- Password: `testpsw`

These are rate-limited and should not be used in production.

---

## License

MIT. See [LICENSE](LICENSE).