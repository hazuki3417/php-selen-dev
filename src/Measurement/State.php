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
 * ストップウォッチの状態を管理するクラス
 */
class State
{
    /**
     * @var bool メモリ使用量、実行時間を保持する配列
     */
    private $state_flg = false;

    /**
     * ストップウォッチの計測を開始します。
     */
    public function run()
    {
        $this->state_flg = true;
    }

    /**
     * ストップウォッチの計測を終了します。
     */
    public function stop()
    {
        $this->state_flg = false;
    }

    /**
     * ストップウォッチの状態を返します。
     *
     * @return bool true:計測中 false:計測終了
     */
    public function get()
    {
        return $this->state_flg;
    }
}
