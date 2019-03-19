<?php

namespace {
    use Amp\Loop;
    use Amp\Parallel\Worker\DefaultPool;
    use Jobs\ComputeJob;

    require_once 'vendor/autoload.php';

    $results = [];
    $n = 100000000;
    $job = new Jobs\ComputeJob( $n);
    $tasks = [
        $job,
        $job,
        $job,
        $job
    ];
    $start = microtime(true);

    Loop::run(function () use (&$results, $tasks) {

        $pool = new DefaultPool;

        $coroutines = [];

        foreach ($tasks as $index => $task) {
            $coroutines[] = Amp\call(function () use ($pool, $index, $task) {
                $result = yield $pool->enqueue($task);
                printf("Async number is %d \n", $result);
                return $result;
            });
        }

        $results = yield Amp\Promise\all($coroutines);

        return yield $pool->shutdown();
    });

    $end = microtime(true);

    $time_taken = $end - $start;
    echo ("\ntime taken " . $time_taken . "\n\n\n");

    $start = microtime(true);
    $cls = new ComputeJob($n);
    echo 'Sync number is ' . $cls->task($n) . "\n";
    echo 'Sync number is ' . $cls->task($n) . "\n";
    echo 'Sync number is ' . $cls->task($n) . "\n";
    echo 'Sync number is ' . $cls->task($n) . "\n";

    $end = microtime(true);

    $time_taken = $end - $start;
    echo ("\ntime taken " . $time_taken . "\n");
}

