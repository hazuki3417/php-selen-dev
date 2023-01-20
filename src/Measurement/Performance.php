<?php

/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 *
 * @package Selen\Dev
 */

namespace Selen\Dev\Measurement;

use Selen\Data\Structure\Queue;
use Selen\Data\Structure\QueueInterface;

/**
 * 処理のパフォーマンスを計測するクラス
 */
class Performance
{
    /**
     * @var QueueInterface インスタンスを保持する変数
     */
    private $queue;

    /**
     * コードのパフォーマンスを計測するインスタンスを生成します。
     */
    public function __construct()
    {
        $this->queue = new Queue(Record::class);
        /**
         * NOTE: 意図的に初期化前に計測レコードの追加処理を行っています
         *       - ここで一旦レコードを追加しないと1回目の計測時間が大きくなる。
         *       - 必要な処理の読み込みなどが発生していることが要因と思われる。
         */
        $this->addRecord();
        $this->init();
    }

    /**
     * 計測処理を実装設定します。
     *
     * @param callable $process 計測処理をクロージャ形式で実装します
     *
     * @return Performance
     */
    public function set(callable $process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * 計測を開始します。
     */
    public function start(int $number = 1)
    {
        for ($inc = 0; $inc < $number; ++$inc) {
            if ($inc === 0) {
                $this->addRecord();
            }
            ($this->process)();
            $this->addRecord();
        }
        return $this;
    }

    /**
     * 計測結果を出力します。
     */
    public function output()
    {
        // 計測結果を出力
        $output  = new RecordTable($this->queue);
        $records = $output->create();

        foreach ($records as $record) {
            echo $record;
        }
    }

    /**
     * 計測情報を初期化します。
     */
    private function init()
    {
        $this->queue->clear();
    }

    /**
     * 計測情報を追加します。
     */
    private function addRecord()
    {
        $this->queue->enqueue(new Record($this->nowMemory(), $this->nowTime()));
    }

    /**
     * 現在のメモリ使用量を取得します。
     *
     * @return int 現在のメモリ使用量を返します
     */
    private function nowMemory()
    {
        return memory_get_peak_usage(true);
    }

    /**
     * 現在のタイムスタンプを取得します。
     *
     * @return float 現在の時刻を返します
     */
    private function nowTime()
    {
        return (float) hrtime(true);
    }
}
