<?php

namespace theodorejb\IISLogParser;

final class IISLogFile
{
    /**
     * @return \Generator<IISLogEntry>
     */
    public static function getEntries(\SplFileObject $file): \Generator
    {
        $fieldsStr = '#Fields: ';
        $fields = [];

        while (!$file->eof()) {
            $line = rtrim($file->fgets());

            if (str_starts_with($line, $fieldsStr)) {
                $fields = explode(' ', substr($line, strlen($fieldsStr)));
            }

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (count($fields) === 0) {
                throw new \Exception("No fields declared in file {$file->getFilename()}");
            }

            yield new IISLogEntry($line, $fields);
        }
    }
}
