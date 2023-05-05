<?php

namespace theodorejb\IISLogParser;

/**
 * @api
 */
class IISLogEntry
{
    public \DateTimeImmutable $date;
    public string $serverIP;
    public string $method;
    public string $uri;
    public string $query;
    public int $serverPort;
    public string $username;
    public string $clientIP;
    public string $useragent;
    public string $referer;
    public string $host;
    public int $statusCode;
    public int $subStatusCode;
    public int $win32StatusCode;
    public int $timeTakenMs;

    /**
     * @param list<array-key> $fields
     */
    public function __construct(string $line, array $fields)
    {
        $values = explode(' ', $line);
        $d = array_combine($fields, $values);

        if (!isset($d['date'], $d['time'])) {
            throw new \Exception('Missing values for date or time');
        }

        $this->date = new \DateTimeImmutable($d['date'] . ' ' . $d['time']);
        $this->serverIP = $d['s-ip'] ?? '';
        $this->method = $d['cs-method'] ?? '';
        $this->uri = $d['cs-uri-stem'] ?? '';
        $this->query = $d['cs-uri-query'] ?? '';
        $this->serverPort = (int) ($d['s-port'] ?? 0);
        $this->username = $d['cs-username'] ?? '';
        $this->clientIP = $d['c-ip'] ?? '';
        $this->useragent = $d['cs(User-Agent)'] ?? '';
        $this->referer = $d['cs(Referer)'] ?? '';
        $this->host = $d['cs-host'] ?? '';
        $this->statusCode = (int) ($d['sc-status'] ?? 0);
        $this->subStatusCode = (int) ($d['sc-substatus'] ?? 0);
        $this->win32StatusCode = (int) ($d['sc-win32-status'] ?? 0);
        $this->timeTakenMs = (int) ($d['time-taken'] ?? 0);
    }

    public function getUriExtension(): string
    {
        $dotPos = strrpos($this->uri, '.');

        if ($dotPos !== false) {
            return strtolower(substr($this->uri, $dotPos));
        }

        return '';
    }
}
