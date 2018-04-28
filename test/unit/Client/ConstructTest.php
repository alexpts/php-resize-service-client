<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

/**
 * @covers \PTS\ServiceResizeClient\Client::__construct()
 */
class ConstructTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testConstruct(): void
    {
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);

        $prop = new ReflectionProperty(Client::class, 'serviceUrl');
        $prop->setAccessible(true);
        $actual = $prop->getValue($client);
        self::assertSame($serviceUrl, $actual);

        $prop = new ReflectionProperty(Client::class, 'http');
        $prop->setAccessible(true);
        $actual = $prop->getValue($client);
        self::assertInstanceOf(HttpClient::class, $actual);
    }
}