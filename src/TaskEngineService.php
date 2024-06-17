<?php

namespace TaskEngine\SDK;

use TaskEngine\SDK\Services\CreateTask;
use TaskEngine\SDK\Services\SendTaskStatus;

class TaskEngineService {
    /**
     * 创建任务
     * @return CreateTask
     */
    public static function createQuery()
    {
        return new CreateTask();
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
