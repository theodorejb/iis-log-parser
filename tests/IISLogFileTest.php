<?php

namespace theodorejb\IISLogParser\Tests;

use PHPUnit\Framework\TestCase;
use theodorejb\IISLogParser\IISLogFile;

class IISLogFileTest extends TestCase
{
    public function testGetFileStats(): void
    {
        $file = new \SplFileObject('tests/files/u_ex230505.log');

        $totalErrors = 0;
        $totalRedirects = 0;
        $staticRequests = 0;
        $staticTimeMs = 0;
        $dynamicRequests = 0;
        $dynamicTimeMs = 0;

        foreach (IISLogFile::getEntries($file) as $entry) {
            $uriExt = $entry->getUriExtension();

            if (!in_array($uriExt, ['', '.php', '.asp'], true)) {
                $staticRequests++;
                $staticTimeMs += $entry->timeTakenMs;
            } else {
                $dynamicRequests++;
                $dynamicTimeMs += $entry->timeTakenMs;
            }

            $hasError = ($entry->statusCode >= 400 || str_contains($entry->query, '|'));

            if ($hasError) {
                $totalErrors++;
            } elseif ($entry->statusCode >= 300) {
                $totalRedirects++;
            }
        }

        $this->assertSame(2, $totalErrors);
        $this->assertSame(33, $totalRedirects);
        $this->assertSame(50, $staticRequests);
        $this->assertSame(363, $staticTimeMs);
        $this->assertSame(28, $dynamicRequests);
        $this->assertSame(1874, $dynamicTimeMs);
    }
}
