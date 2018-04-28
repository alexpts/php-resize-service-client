<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

/**
 * @covers \PTS\ServiceResizeClient\Client::fit()
 */
class FitTest extends TestCase
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
    public function testFit(array $args, array $params): void
    {
        $client = $this->client;
        $actual = $client->fit(...$args);
        self::assertInstanceOf(Client::class, $actual);

        $prop = new ReflectionProperty(Client::class, 'commands');
        $prop->setAccessible(true);
        /** @var Command[] $commands */
        $commands = $prop->getValue($client);

        self::assertCount(1, $commands);

        $command = $commands[0];
        self::assertSame('fit', $command->getName());
        self::assertSame($params, $command->getParams());

    }

    public function dataProvider(): array
    {
        return [
            [
                [200, 200, 'center', 'top'],
                ['w' => 200, 'h' => 200, 'posX' => 'center', 'posY' => 'top'],
            ],
            [
                [200, 300, 20, 'bottom'],
                ['w' => 200, 'h' => 300, 'posX' => 20, 'posY' => 'bottom'],
            ],
            [
                [null, 300, 'left', -30],
                ['h' => 300, 'posX' => 'left', 'posY' => -30],
            ],
            [
                [200, null, null, null],
                ['w' => 200],
            ],
        ];
    }
}