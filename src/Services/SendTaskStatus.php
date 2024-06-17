<?php

namespace TaskEngine\SDK\Services;

class SendTaskStatus
{
    /**
     * 返回任务保活的结构体
     * @return array 
     */
    public static function keepAlive()
    {
        return ['nextStatus' => 'keepalive'];
    }

    /**
     * 返回任务完成的结构体
     * @return array
     */
    public static function complete()
    {
        return ['nextStatus' => 'complete'];
    }
}
