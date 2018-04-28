<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

/**
 * @covers \PTS\ServiceResizeClient\Client::sharp()
 */
class SharpTest extends TestCase
{
    /** @var Client */
    protected $client;

    /**
     * @throws ReflectionException
     *
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();

        $http = $this->createMock(HttpClient::class);
        $this->client = new Client('http://some.com', $http);
    }

    /**
     * @param array $args
     * @param array $params
     *
     * @dataProvider dataProvider
     *
     * @throws ReflectionException
     */
    public function testSharp(array $args, array $params): void
    {
        $client = $this->client;
        $actual = $client->sharp(...$args);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'commands');
        $prop->setAccessible(true);
        /** @var Command[] $commands */
        $commands = $prop->getValue($client);

        self::assertCount(1, $commands);

        $command = $commands[0];
        self::assertSame('sharp', $command->getName());
        self::assertSame($params, $command->getParams());

    }

    public function dataProvider(): array
    {
        return [
            [
                [1, 1, 1, 1, 1, 1],
                ['sigma' => 1.0, 'x1' => 1.0, 'y2' => 1.0, 'y3' => 1.0, 'm1' => 1.0, 'm2' => 1.0],
            ],
            [
                [1, 1.3, null, null, 1, 1],
                ['sigma' => 1.0, 'x1' => 1.3, 'm1' => 1.0, 'm2' => 1.0],
            ],
            [
                [1.2],
                ['sigma' => 1.2],
            ],

        ];
    }
}