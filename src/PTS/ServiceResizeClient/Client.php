<?php
declare(strict_types=1);

namespace PTS\ServiceResizeClient;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /** @var HttpClient */
    protected $http;
    /** @var string */
    protected $serviceUrl = 'http://127.0.0.1/image/';

    /** @var string */
    protected $path = '';
    /** @var Command[] */
    protected $commands = [];
    /** @var string */
    protected $format = 'jpg';
    /** @var int */
    protected $quality = 85;

    /**
     * @param string $serviceUrl
     * @param HttpClient $http
     */
    public function __construct(string $serviceUrl, HttpClient $http)
    {
        $this->serviceUrl = $serviceUrl;
        $this->http = $http;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function path(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->path = '';
        $this->resetCommands();
        $this->format = 'jpg';
        $this->quality = 85;

        return $this;
    }

    /**
     * @return $this
     */
    public function resetCommands(): self
    {
        $this->commands = [];

        return $this;
    }

    /**
     * @param string $format
     *
     * @return $this
     *
     * @throws \UnexpectedValueException
     */
    public function format(string $format): self
    {
        $enum = new Format($format);
        $this->format = $enum->getValue();

        return $this;
    }

    /**
     * @param int|null $w
     * @param int|null $h
     *
     * @return Client
     */
    public function resize(?int $w, ?int $h): self
    {
        $command = new Command('resize', [
            'w' => $w,
            'h' => $h,
        ]);
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return $this
     */
    public function crop(int $x = 0, int $y = 0): self
    {
        $command = new Command('resize', [
            'x' => $x,
            'y' => $y,
        ]);
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @param int|null $w
     * @param int|null $h
     * @param string|int|null $posX
     * @param string|int|null $posY
     *
     * @return $this
     */
    public function fit(int $w, int $h, $posX = 'center', $posY = 'center'): self
    {
        $command = new Command('fit', [
            'w'    => $w,
            'h'    => $h,
            'posX' => $posX,
            'posY' => $posY,
        ]);
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @param float|null $sigma
     * @param float|null $x1
     * @param float|null $y2
     * @param float|null $y3
     * @param float|null $m1
     * @param float|null $m2
     *
     * @see http://jcupitt.github.io/libvips/API/current/libvips-convolution.html#vips-sharpen
     *
     * @return Client
     */
    public function sharp(
        float $sigma = null,
        float $x1 = null,
        float $y2 = null,
        float $y3 = null,
        float $m1 = null,
        float $m2 = null
    ): self {
        $command = new Command('sharp', [
            'sigma' => $sigma,
            'x1'    => $x1,
            'y2'    => $y2,
            'y3'    => $y3,
            'm1'    => $m1,
            'm2'    => $m2,
        ]);
        $this->commands[] = $command;

        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws \RuntimeException
     * @throws RemoteException
     * @throws GuzzleException
     */
    public function getResponse(): ResponseInterface
    {
        $response = $this->http->request('GET', $this->serviceUrl, [
            'query' => $this->createQueryParams()
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new RemoteException('Service error', $response->getStatusCode());
        }

        return $response;
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     * @throws RemoteException
     * @throws GuzzleException
     */
    public function getImage(): string
    {
        $response = $this->getResponse();

        return $response->getBody()->getContents();
    }

    protected function createQueryParams(): array
    {
        $params = array_reduce($this->commands, function (array $acc, Command $command) {
            $name = $command->getName();
            $acc[$name] = $command->getParamsQuery();

            return $acc;
        }, []);

        $params['path'] = $this->path;
        $params['format'] = $this->format;

        if ($this->quality > 0 && $this->quality <= 100) {
            $params['q'] = $this->quality;
        }

        return $params;
    }
}
