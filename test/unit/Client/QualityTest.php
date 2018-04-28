<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use PTS\ServiceResizeClient\Client;

/**
 * @covers \PTS\ServiceResizeClient\Client::quality()
 */
class QualityTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testQuality(): void
    {
        $qualityValue = 43;
        $serviceUrl = 'http://127.0.0.1';
        $http = $this->createMock(HttpClient::class);

        $client = new Client($serviceUrl, $http);
        $actual = $client->quality($qualityValue);
        self::assertInstanceOf(Client::class, $actual);

        $quality = new ReflectionProperty(Client::class, 'quality');
        $quality->setAccessible(true);
        self::assertSame($qualityValue, $quality->getValue($client));
    }
}