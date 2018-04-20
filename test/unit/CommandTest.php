<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PTS\ServiceResizeClient\Command;

class CommandTest extends TestCase
{
    /**
     * @param string $name
     * @param array  $params
     *
     * @throws \ReflectionException
     *
     * @dataProvider constructDataProvider
     */
    public function testConstruct(string $name, array $params): void
    {
        $command = new Command($name, $params);

        $prop = new \ReflectionProperty(Command::class, 'name');
        $prop->setAccessible(true);
        self::assertSame($name, $prop->getValue($command));

        $prop = new \ReflectionProperty(Command::class, 'params');
        $prop->setAccessible(true);
        self::assertSame($params, $prop->getValue($command));
    }

    public function constructDataProvider(): array
    {
        return [
            [
                'fit',
                []
            ],
            [
                'crop',
                ['x' => 'top', 'y' => 50]
            ],
            [
                'resize',
                ['w' => 300]
            ],
        ];
    }

    /**
     * @param string $name
     * @param array $params
     *
     * @dataProvider constructDataProvider
     */
    public function testGetName(string $name, array $params): void
    {
        $command = new Command($name, $params);
        self::assertSame($name, $command->getName());
    }

    /**
     * @param string $name
     * @param array $params
     *
     * @dataProvider getParamsQueryDataProvider
     */
    public function testGetParams(string $name, array $params)
    {
        $command = new Command($name, $params);
        $actual = $command->getParams();

        self::assertSame($actual, $params);
    }

    /**
     * @param string $expected
     * @param array $params
     *
     * @dataProvider getParamsQueryDataProvider
     */
    public function testGetParamsQuery(string $expected, array $params): void
    {
        $command = new Command('command', $params);
        self::assertSame($expected, $command->getParamsQuery());
    }

    public function getParamsQueryDataProvider(): array
    {
        return [
            [
                '',
                []
            ],
            [
                'x:top,y:50',
                ['x' => 'top', 'y' => 50]
            ],
            [
                'w:300',
                ['w' => 300]
            ],
            [
                'w:300,h:300,posX:center',
                ['w' => 300, 'h' => 300, 'posX' => 'center']
            ],
        ];
    }
}