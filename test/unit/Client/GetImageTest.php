<?php
declare(strict_types=1);

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PTS\ServiceResizeClient\Client;

/**
 * @covers \PTS\ServiceResizeClient\Client::getImage()
 */
class GetImageTest extends TestCase
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \PTS\ServiceResizeClient\RemoteException
     */
    public function testGetResponse(): void
    {
        $content = 'blob';
        $body = $this->getMockBuilder(StreamInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContents'])
            ->getMockForAbstractClass();
        $body->expects(self::once())->method('getContents')->willReturn($content);

        $response = $this->getMockBuilder(ResponseInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getBody'])
            ->getMockForAbstractClass();
        $response->expects(self::once())->method('getBody')->willReturn($body);

        /** @var MockObject|Client $client */
        $client = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->setMethods(['getResponse'])
            ->getMock();
        $client->expects(self::once())->method('getResponse')->willReturn($response);

        $actual = $client->getImage();
        self::assertSame($content, $actual);


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
