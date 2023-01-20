<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Selen\Dev\Measurement\Performance;

$perf1 = new Performance();

$perf1->set(function () {
    $sum    = '1';
    $result = 0;

    for ($i = 1; $i < 10000; ++$i) {
        $result += $sum;
    }
})->start(8);

// $perf2 = new Performance();

// $perf2->set(function () {
//     $sum = 1;
//     $result = 0;

//     for ($i = 1; $i < 10000; ++$i) {
//         $result += $sum;
//     }
// })->start(8);
