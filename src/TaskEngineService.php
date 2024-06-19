<?php

namespace TaskEngine\SDK;

use TaskEngine\SDK\Services\QueryFactory;
use TaskEngine\SDK\Services\SendTaskStatus;

class TaskEngineService {
    /**
     * 创建任务
     * @return CreateTask
     */
    public static function query()
    {
        return new QueryFactory();
    }

    /**
     * 任务状态改为保活状态
     * @return array
     */
    public static function sendKeepAliveStatus()
    {
        return SendTaskStatus::keepAlive();
    }

    /**
     * 任务状态改为完成状态
     * @return array
     */
    public static function sendCompleteStatus()
    {
        return SendTaskStatus::complete();
    }
}
