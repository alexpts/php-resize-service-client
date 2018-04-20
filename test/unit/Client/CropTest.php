<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

class CropTest extends TestCase
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
    public function testCrop(array $args, array $params): void
    {
        $client = $this->client;
        $actual = $client->crop(...$args);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'commands');
        $prop->setAccessible(true);
        /** @var Command[] $commands */
        $commands = $prop->getValue($client);

        self::assertCount(1, $commands);

        $command = $commands[0];
        self::assertSame('crop', $command->getName());
        self::assertSame($params, $command->getParams());

    }

    public function dataProvider(): array
    {
        return [
            [
                [200, 200],
                ['x' => 200, 'y' => 200],
            ],
            [
                [400, 200],
                ['x' => 400, 'y' => 200],
            ],
            [
                [400, 0],
                ['x' => 400, 'y' => 0],
            ],
            [
                [0, 300],
                ['x' => 0, 'y' => 300],
            ],
        ];
    }
}