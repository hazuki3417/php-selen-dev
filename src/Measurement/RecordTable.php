<?php

/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 *
 * @package Selen\Dev
 */

namespace Selen\Dev\Measurement;

use Selen\Data\Memo\Str\MaxLength;
use Selen\Data\Structure\QueueInterface;

/**
 * 計測結果を出力するクラス
 */
class RecordTable
{
    /**
     * @var QueueInterface インスタンスを保持する変数
     */
    private $queue;

    /**
     * コンストラクタ
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * 計測結果を出力します。
     *
     * @return array
     */
    public function create()
    {
        $convertedRecords = $this->convert();

        $header = [
            '',
            'process(1)[s]',
            'process(t)[s]',
            'memory(1)[MB]',
            'memory(t)[MB]',
        ];

        /**
         * 最大長の文字列を取得する処理
         */
        $performanceMaxLength = new MaxLength();
        $processOneMaxLength  = new MaxLength();
        $processSumMaxLength  = new MaxLength();
        $memoryOneMaxLength   = new MaxLength();
        $memorySumMaxLength   = new MaxLength();

        $performanceMaxLength->set($header[0]);
        $processOneMaxLength->set($header[1]);
        $processSumMaxLength->set($header[2]);
        $memoryOneMaxLength->set($header[3]);
        $memorySumMaxLength->set($header[4]);

        foreach ($convertedRecords as $record) {
            $performanceMaxLength->set($record[0]);
            $processOneMaxLength->set($record[1]);
            $processSumMaxLength->set($record[2]);
            $memoryOneMaxLength->set($record[3]);
            $memorySumMaxLength->set($record[4]);
        }

        $performanceStrLength = \mb_strlen($performanceMaxLength->get());
        $processOneStrLength  = \mb_strlen($processOneMaxLength->get());
        $processSumStrLength  = \mb_strlen($processSumMaxLength->get());
        $memoryOneStrLength   = \mb_strlen($memoryOneMaxLength->get());
        $memorySumStrLength   = \mb_strlen($memorySumMaxLength->get());

        $outputRecords = [];

        $padStr    = ' ';
        $separator = '|';
        $newLine   = "\n";

        $paddingHeader = [
            \str_pad($header[0], $performanceStrLength, $padStr, \STR_PAD_BOTH),
            \str_pad($header[1], $processOneStrLength, $padStr, \STR_PAD_BOTH),
            \str_pad($header[2], $processSumStrLength, $padStr, \STR_PAD_BOTH),
            \str_pad($header[3], $memoryOneStrLength, $padStr, \STR_PAD_BOTH),
            \str_pad($header[4], $memorySumStrLength, $padStr, \STR_PAD_BOTH),
        ];

        $paddingBorder = [
            \str_pad('', $performanceStrLength, '-', \STR_PAD_BOTH),
            \str_pad('', $processOneStrLength, '-', \STR_PAD_BOTH),
            \str_pad('', $processSumStrLength, '-', \STR_PAD_BOTH),
            \str_pad('', $memoryOneStrLength, '-', \STR_PAD_BOTH),
            \str_pad('', $memorySumStrLength, '-', \STR_PAD_BOTH),
        ];

        $outputHeader = $separator . \implode($separator, $paddingHeader) . $separator . $newLine;

        $outputBorder = $separator . \implode($separator, $paddingBorder) . $separator . $newLine;

        foreach ($convertedRecords as $record) {
            $paddingRecord = [
                \str_pad($record[0], $performanceStrLength, $padStr, \STR_PAD_RIGHT),
                \str_pad($record[1], $processOneStrLength, $padStr, \STR_PAD_LEFT),
                \str_pad($record[2], $processSumStrLength, $padStr, \STR_PAD_LEFT),
                \str_pad($record[3], $memoryOneStrLength, $padStr, \STR_PAD_LEFT),
                \str_pad($record[4], $memorySumStrLength, $padStr, \STR_PAD_LEFT),
            ];

            $outputRecords[] = $separator . \implode($separator, $paddingRecord) . $separator . $newLine;
        }

        return \array_merge([$outputHeader], [$outputBorder], $outputRecords);
    }

    /**
     * 計測結果を出力用に変換します
     */
    private function convert()
    {
        $mesRecord = $this->queue->dequeue();

        $baseTime   = $mesRecord->getTime();
        $baseMemory = $mesRecord->getMemory();
        $prevTime   = $baseTime;
        $prevMemory = $baseMemory;

        $count = $this->queue->size();

        for ($i = 0; $i < $count; ++$i) {
            $record = $this->queue->dequeue();

            $targetTime   = $record->getTime();
            $targetMemory = $record->getMemory();

            /**
             * NOTE: time -> 正数表現のナノ秒を小数表現のナノ秒に変換
             *       memory -> バイト表現をメガバイト表現に変換
             */
            $lapTime    = ($targetTime - $prevTime)     / 1e+9;
            $lapMemory  = ($targetMemory - $prevMemory) / 1e+6;
            $diffTime   = ($targetTime - $baseTime)     / 1e+9;
            $diffMemory = ($targetMemory - $baseMemory) / 1e+6;

            $records[] = [
                \sprintf('lap(%s)', $i + 1),
                \sprintf('%.9f', $lapTime),
                \sprintf('%.9f', $diffTime),
                \sprintf('%.3f', $lapMemory),
                \sprintf('%.3f', $diffMemory),
            ];

            // 前回値を保持
            $prevTime   = $targetTime;
            $prevMemory = $targetMemory;
        }

        return $records;
    }
}
