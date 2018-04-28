<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\Command;

/**
 * @covers \PTS\ServiceResizeClient\Client::createQueryParams()
 */
class CreateQueryParamsTest extends TestCase
{

    /**
     * @param Command[] $commands
     * @param array     $expected
     *
     * @dataProvider dataProvider
     *
     * @throws ReflectionException
     */
    public function testCreateQueryParams(array $commands, array $expected): void
    {
        $http = $this->createMock(HttpClient::class);
        $client = new Client('http://some.com', $http);

        foreach ($commands as $command) {
            $client->pushCommand($command);
        }

        $method = new ReflectionMethod(Client::class, 'createQueryParams');
        $method->setAccessible(true);
        $actual = $method->invoke($client);

        self::assertInternalType('array', $actual);
        self::assertSame($expected, $actual);
    }

    public function dataProvider(): array
    {
        $base = [
            'path' => '',
            'format' => 'jpg',
            'q' => 85
        ];

        return [
            [
                [],
                $base,
            ],
            [
                [
                    new Command('crop', ['x' => 10]),
                ],
                array_merge(['crop' => 'x:10'], $base),
            ],
            [
                [
                    new Command('crop', ['x' => 10, 'y' => 20]),
                    new Command('fit', ['w' => 300, 'h' => 400]),
                ],
                array_merge(['crop' => 'x:10,y:20', 'fit' => 'w:300,h:400'], $base),
            ],
        ];
    }
}