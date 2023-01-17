<?php

/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 *
 * @package Selen\Dev
 */

namespace Selen\Dev\Measurement;

/**
 * メモリ使用量、実行時間を保持するクラス
 */
class Record
{
    /**
     * @var int メモリ使用量を保持
     */
    private $memory = 0;

    /**
     * @var float 実行時間を保持
     */
    private $time = 0;

    /**
     * コンストラクタ
     *
     * @param int $memory メモリ量を渡します
     * @param float $time 実行時間を渡します
     */
    public function __construct(int $memory, float $time)
    {
        $this->memory = $memory;
        $this->time = $time;
    }

    /**
     * メモリ量を取得します。
     *
     * @return int メモリ量を返します
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * 実行時間量を取得します。
     *
     * @return int 実行時間を返します
     */
    public function getTime()
    {
        return $this->time;
    }
}
