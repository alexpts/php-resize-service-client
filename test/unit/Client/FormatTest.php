<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

class FormatTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testFormat(): void
    {
        $format = 'webp';
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);
        $actual = $client->format($format);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'format');
        $prop->setAccessible(true);
        $actual = $prop->getValue($client);
        self::assertSame($format, $actual);
    }

    /**
     * @throws ReflectionException
     */
    public function testUnknownFormat(): void
    {
        $format = 'webp42';
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $this->expectException(UnexpectedValueException::class);

        $client = new Client($serviceUrl, $http);
        $client->format($format);
    }
}