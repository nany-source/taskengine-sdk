<?php

namespace TaskEngine\SDK;

use TaskEngine\SDK\Services\CreateTask;
use TaskEngine\SDK\Services\SendTaskStatus;

class TaskEngineService {
    public static function createQuery()
    {
        return new CreateTask();
    }

    public static function sendStatusQuery()
    {
        return new SendTaskStatus();
    }
}
