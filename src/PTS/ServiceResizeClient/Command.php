<?php
declare(strict_types=1);

namespace PTS\ServiceResizeClient;

class Command
{
    /** @var string */
    protected $name;
    /** @var array */
    protected $params = [];

    public function __construct(string $name, array $params)
    {
        $this->name = $name;
        $this->params = array_filter($params, function ($value) {
            return $value !== null;
        });
    }

    public function getParamsQuery(): string
    {
        $paramsQuery = [];
        foreach ($this->params as $name => $val) {
            $paramsQuery[] = sprintf('%s:%s', $name, $val);
        }

        return implode(',', $paramsQuery);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
