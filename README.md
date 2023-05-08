# IIS Log Parser

This package makes it easy to parse IIS log files using PHP.
Supports the standard W3C log file format with default fields as well as the optional Host field.

## Install via Composer

`composer require theodorejb/iis-log-parser`

## Usage

Construct an `SplFileObject` instance for the IIS log file to be parsed.
Then call the `IISLogFile::getEntries` static method, passing it the `SplFileObject`.
This returns a generator which yields an `IISLogEntry` object for each entry in the file.

Code example which iterates over the entries of all log files in a directory:

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

## Required fields

Only the Date and Time logging fields (used for the `$date` property) are required.
Other properties will be set to a blank string (or 0, for int properties)
if the associated logging field is absent.
