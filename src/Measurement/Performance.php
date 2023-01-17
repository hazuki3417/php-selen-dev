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
     * @var State インスタンスを保持する変数
     */
    private $state;

    /**
     * @var QueueInterface インスタンスを保持する変数
     */
    private $queue;

    /**
     * コードのパフォーマンスを計測するインスタンスを生成します。
     */
    public function __construct()
    {
        $this->state = new State();
        $this->queue = new Queue(Record::class);
        $this->init();
    }

    /**
     * 計測処理を実装設定します。
     *
     * @param callable $process 計測処理をクロージャ形式で実装します
     *
     * @return Performance
     */
    public function set($process)
    {
        $this->process = $process;
        return $this;
    }

    /**
     * 計測を開始します。
     *
     * @param mixed $number
     */
    public function start($number = 1)
    {
        // コールバックメソッドを取得
        $method = $this->process;

        $this->state->run();

        $this->addRecord();

        for ($i = 0; $i < $number; ++$i) {
            // コールバックメソッド実行
            $method();
            $this->addRecord();
        }

        $this->state->stop();

        // TODO: 平均値を計算して出力する処理を実装する

        // 計測結果を出力
        $output = new RecordTable($this->queue);
        $records = $output->create();

        foreach ($records as $record) {
            echo $record;
        }
    }

    /**
     * Stopwatchの内部情報を初期化します。
     */
    private function init()
    {
        $this->state->stop();
        $this->queue->clear();
    }

    /**
     * 計測記録を追加します。
     */
    private function addRecord()
    {
        $record = new Record($this->nowMemory(), $this->nowTime());
        $this->queue->enqueue($record);
    }

    /**
     * 現在のメモリ使用量を取得します。
     *
     * @return int 現在のメモリ使用量を返します
     */
    private function nowMemory()
    {
        return memory_get_peak_usage();
    }

    /**
     * 現在のタイムスタンプを取得します。
     *
     * @return float 現在の時刻を返します
     */
    private function nowTime()
    {
        return microtime(true);
    }
}
