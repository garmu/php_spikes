<?php

namespace Jobs;

use Amp\Parallel\Worker\Environment;
use Amp\Parallel\Worker\Task;

class ComputeJob implements Task
{
    /** @var int */
    private $n;

    /**
     * ComputeJob constructor.
     * @param int $n
     */
    public function __construct($n)
    {
        $this->n = $n;
    }

    /**
     * @param Environment $environment
     * @return \Amp\Promise|\Generator|mixed|void
     */
    public function run(Environment $environment)
    {
        return $this->task($this->n);
    }

    public function task($n) {
        $total = 0;
        for ($i=0; $i<$n; $i++) {
            $total += $i;
        }
        return $total;
    }
}