<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

class ResetCommandsTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testResetCommand(): void
    {
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);
        $client->resize(200, 200);
        $actual = $client->resetCommands();
        self::assertInstanceOf(Client::class, $actual);

        $commands = new ReflectionProperty(Client::class, 'commands');
        $commands->setAccessible(true);
        self::assertSame([], $commands->getValue($client));
    }
}