<?php

namespace TaskEngine\SDK\Services;

class BaseServices
{
    /**
     * 设置条件
     * @param bool $condition 条件
     * @param callable $callback 符合条件时回调
     * @param callable|null $defaultCallBack 不符合条件时回调
     * @return CreateTask
     */
    public function when($condition, $callback, $defaultCallBack = null)
    {
        if ($condition) {
            $callback($this, $condition);
        } else if ($defaultCallBack) {
            $defaultCallBack($this, $condition);
        }
        return $this;
    }
}
