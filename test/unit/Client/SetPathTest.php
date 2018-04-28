<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

/**
 * @covers \PTS\ServiceResizeClient\Client::path()
 */
class SetPathTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testPath(): void
    {
        $path = 'folder/imageName.jpg';
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);
        $actual = $client->path($path);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'path');
        $prop->setAccessible(true);
        $actual = $prop->getValue($client);
        self::assertSame($path, $actual);
    }
}
