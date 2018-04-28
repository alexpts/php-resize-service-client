<?php
declare(strict_types=1);

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use PTS\ServiceResizeClient\Client;
use PTS\ServiceResizeClient\RemoteException;

/**
 * @covers \PTS\ServiceResizeClient\Client::getResponse()
 */
class GetResponseTest extends TestCase
{
    /**
     * @param int $statusCode
     * @param array $params
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PTS\ServiceResizeClient\RemoteException
     *
     * @dataProvider dataProvider
     */
    public function testGetResponse(int $statusCode, array $params): void
    {
        $serviceUrl = 'http://some.com/image/';

        if ($statusCode !== 200) {
            $this->expectException(RemoteException::class);
            $this->expectExceptionMessage('Service error');
            $this->expectExceptionCode($statusCode);
        }

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getStatusCode'])
            ->getMockForAbstractClass();
        $response->expects(self::once())->method('getStatusCode')->willReturn($statusCode);

        $http = $this->getMockBuilder(HttpClient::class)
            ->disableOriginalConstructor()
            ->setMethods(['request'])
            ->getMock();
        $http->expects(self::once())->method('request')->with('GET', $serviceUrl, ['query' => $params])->willReturn($response);

        /** @var MockObject|Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->setConstructorArgs([$serviceUrl, $http])
            ->setMethods(['createQueryParams'])
            ->getMock();
        $client->expects(self::once())->method('createQueryParams')->willReturn($params);

        $actual = $client->getResponse();
        self::assertInstanceOf(ResponseInterface::class, $actual);
    }

    public function dataProvider(): array
    {
        return [
            [200, []],
            [500, []],
            [401, ['crop=x:2,y2']],
        ];
    }
}
