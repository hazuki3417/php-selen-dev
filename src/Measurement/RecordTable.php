<?php

/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 *
 * @package Selen\Dev
 */

namespace Selen\Dev\Measurement;

use Selen\Data\Structure\QueueInterface;

/**
 * 計測結果を出力するクラス
 */
class RecordTable
{
    public const OUTPUT_TYPE_HTML = 'html';
    public const OUTPUT_TYPE_LOG = 'log';
    public const OUTPUT_TYPE_TERMINAL = 'terminal';

    public const MESSAGE_FORMAT_HEADER = '| %-12s | %16s | %16s | %14s | %14s |';
    public const MESSAGE_FORMAT_RESULT = '| %-12s | %16.12f | %16.12f | %14.3f | %14.3f |';

    /**
     * @var string 改行コードを保持する変数
     */
    private $new_line = "\n";

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
     * 計測結果の出力形式を指定します。
     *
     * @param string $type
     *
     * @throws invalidArgumentException 対応していない出力形式を指定したときに発生します
     */
    public function outputType($type)
    {
        switch ($type) {
            case self::OUTPUT_TYPE_TERMINAL:
                $this->new_line = "\n";
                break;
                // case self::OUTPUT_TYPE_HTML:
            //     $this->new_line = "</br>";
            //     break;
                // case self::OUTPUT_TYPE_LOG:
            //     // phpのエラーログ設定を一時的に変更
            //     ini_set('log_errors', 'On');
            //     ini_set('error_log', self::PHP_ERROR_LOG_PATH);
            //     break;
            default:
                throw new \InvalidArgumentException('The value is incorrect. $type');
        }
    }

    /**
     * 計測結果を出力します。
     *
     * @return array
     */
    public function create()
    {
        $records = [];
        $records[] = sprintf(
            self::MESSAGE_FORMAT_HEADER . $this->new_line,
            '',
            'process(1)[s]',
            'process(t)[s]',
            'memory(1)[MB]',
            'memory(t)[MB]'
        );

        // 最初の記録は基準値として利用するため先に取得
        $record = null;

        try {
            $record = $this->queue->dequeue();
        } catch (\Exception $e) {
            return $records;
        }

        $base_time = $record->getTime();
        $base_memory = $record->getMemory();
        $prev_time = $base_time;
        $prev_memory = $base_memory;

        // 2つ目から基準値との差分を出力する
        $count = $this->queue->size();

        for ($i = 0; $i < $count; ++$i) {
            $record = $this->queue->dequeue();

            $target_time = $record->getTime();
            $target_memory = $record->getMemory();

            $lap_time = $target_time - $prev_time;
            $lap_memory = $target_memory - $prev_memory;
            $diff_time = $target_time - $base_time;
            $diff_memory = $target_memory - $base_memory;

            $records[] = sprintf(
                self::MESSAGE_FORMAT_RESULT . $this->new_line,
                \sprintf('lap(%s)', $i + 1),
                $lap_time,
                $diff_time,
                $lap_memory,
                $diff_memory
            );
            // 前回値を保持
            $prev_time = $target_time;
            $prev_memory = $target_memory;
        }
        return $records;
    }
}
