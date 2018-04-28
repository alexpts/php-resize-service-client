<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

/**
 * @covers \PTS\ServiceResizeClient\Client::pushCommand()
 */
class PushCommandTest extends TestCase
{

    /**
     * @throws ReflectionException
     */
    public function testPushCommand(): void
    {
        $params = ['param1' => 'val1'];

        $http = $this->createMock(HttpClient::class);
        $client = new Client('http://some.com', $http);

        $client->pushCommand(new Command('some', $params));

        $prop = new ReflectionProperty(Client::class, 'commands');
        $prop->setAccessible(true);
        /** @var Command[] $commands */
        $commands = $prop->getValue($client);

        self::assertCount(1, $commands);

        $command = $commands[0];
        self::assertSame('some', $command->getName());
        self::assertSame($params, $command->getParams());
    }
}
