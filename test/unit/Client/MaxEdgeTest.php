<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

/**
 * @covers \PTS\ServiceResizeClient\Client::maxEdge()
 */
class MaxEdgeTest extends TestCase
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
    public function testMaxEdge(array $args, array $params): void
    {
        $client = $this->client;
        $actual = $client->maxEdge(...$args);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'commands');
        $prop->setAccessible(true);
        /** @var Command[] $commands */
        $commands = $prop->getValue($client);

        self::assertCount(1, $commands);

        $command = $commands[0];
        self::assertSame('maxEdge', $command->getName());
        self::assertSame($params, $command->getParams());

    }

    public function dataProvider(): array
    {
        return [
            [
                [200, 200],
                ['w' => 200, 'h' => 200],
            ],
            [
                [100, 200],
                ['w' => 100, 'h' => 200],
            ],
            [
                [200, 100],
                ['w' => 200, 'h' => 100],
            ],
        ];
    }
}