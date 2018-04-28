<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

/**
 * @covers \PTS\ServiceResizeClient\Client::reset()
 */
class ResetTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testReset(): void
    {
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);
        $client->path('somePath.jpg')->quality(100)->format('webp')->resize(200, null);
        $actual =$client->reset();
        self::assertInstanceOf(Client::class, $actual);

        $path = new ReflectionProperty(Client::class, 'path');
        $path->setAccessible(true);
        self::assertSame('', $path->getValue($client));

        $commands = new ReflectionProperty(Client::class, 'commands');
        $commands->setAccessible(true);
        self::assertSame([], $commands->getValue($client));

        $format = new ReflectionProperty(Client::class, 'format');
        $format->setAccessible(true);
        self::assertSame('jpg', $format->getValue($client));

        $quality = new ReflectionProperty(Client::class, 'quality');
        $quality->setAccessible(true);
        self::assertSame(85, $quality->getValue($client));
    }
}
