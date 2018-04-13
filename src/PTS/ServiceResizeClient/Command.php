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
        $this->params = $params;
    }

    public function getParamsQuery(): string
    {
        $params = [];
        foreach ($this->params as $name => $val) {
            $params[] = sprintf('%s:%s', $name, $val);
        }

        return implode(',', $params);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
