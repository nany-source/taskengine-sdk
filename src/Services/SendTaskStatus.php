<?php

namespace TaskEngine\SDK\Services;

class SendTaskStatus
{
    const CALLBACK_TASKSTATUS = [
        'keepalive' => 1,
        'complete' => 2,
    ];

    /**
     * 返回任务保活的结构体
     * @return void 
     */
    public static function keepAlive()
    {
        return ['nextStatus' => self::CALLBACK_TASKSTATUS['keepalive']];
    }

    /**
     * 返回任务完成的结构体
     * @return void 
     */
    public static function complete()
    {
        return ['nextStatus' => self::CALLBACK_TASKSTATUS['complete']];
    }
}
