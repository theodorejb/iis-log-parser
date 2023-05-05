# IIS Log Parser

This package makes it easy to parse IIS log files using PHP.
Supports the standard W3C log file format with default fields as well as the optional Host field.

## Install via Composer

`composer require theodorejb/iis-log-parser`

## Usage

Construct an `IISLogFile` instance with an `SplFileObject` object:

```php
use theodorejb\IISLogParser\IISLogFile;

$directory = new \FilesystemIterator('C:/inetpub/logs/LogFiles/W3SVC1');

while ($directory->valid()) {
    $current = $directory->current();
    echo "Processing {$current->getFilename()}\n";
    
    $entries = IISLogFile::getEntries($current->openFile());

    foreach ($entries as $entry) {
        echo "Request to {$entry->uri} occurred on {$entry->date->format(DATE_ATOM)}\n";
    }

    echo "\n";
    $directory->next();
}
```

The `IISLogEntry` class has the following public properties:

* `DateTimeImmutable $date`
* `string $serverIP`
* `string $method`
* `string $uri`
* `string $query`
* `int $serverPort`
* `string $username`
* `string $clientIP`
* `string $useragent`
* `string $referer`
* `string $host`
* `int $statusCode`
* `int $subStatusCode`
* `int $win32StatusCode`
* `int $timeTakenMs`

It also has a public `getUriExtension()` method.
