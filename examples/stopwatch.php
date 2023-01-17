<?php

include dirname(__DIR__) . '/vendor/autoload.php';

use Selen\Dev\Measurement\Stopwatch;

$stopwatch = new Stopwatch();

$stopwatch->start();

sleep(1);
$stopwatch->lap();

sleep(2);
$stopwatch->lap();

sleep(3);
$stopwatch->lap();

sleep(4);
$stopwatch->lap();

sleep(5);
$stopwatch->lap();

sleep(10);
$stopwatch->stop();

$stopwatch->output();

/*
output result

|              |    process(1)[s] |    process(t)[s] |  memory(1)[MB] |  memory(t)[MB] |
| test         |   1.004815101624 |   1.004815101624 |          0.000 |          0.000 |
| test         |   2.003362894058 |   3.008177995682 |          0.000 |          0.000 |
| test         |   3.000219106674 |   6.008397102356 |          0.000 |          0.000 |
| test         |   4.000391960144 |  10.008789062500 |          0.000 |          0.000 |
| test         |   5.002691984177 |  15.011481046677 |          0.000 |          0.000 |
| test         |  10.004261016846 |  25.015742063522 |          0.000 |          0.000 |

*/

$stopwatch->start();

sleep(1);
$stopwatch->lap();

$stopwatch->stop();

$stopwatch->output();

/*
output result (stopwatch restart case)

|              |    process(1)[s] |    process(t)[s] |  memory(1)[MB] |  memory(t)[MB] |
| test         |   1.000257968903 |   1.000257968903 |          0.000 |          0.000 |
| test         |   2.001698970795 |   3.001956939697 |          0.000 |          0.000 |
| test         |   3.003587961197 |   6.005544900894 |          0.000 |          0.000 |
| test         |   4.004867076874 |  10.010411977768 |          0.000 |          0.000 |
| test         |   5.002783060074 |  15.013195037842 |          0.000 |          0.000 |
| test         |  10.004489898682 |  25.017684936523 |          0.000 |          0.000 |
|              |    process(1)[s] |    process(t)[s] |  memory(1)[MB] |  memory(t)[MB] |
| test         |   1.004171133041 |   1.004171133041 |          0.000 |          0.000 |
| test         |   0.000007867813 |   1.004179000854 |          0.000 |          0.000 |
 */
