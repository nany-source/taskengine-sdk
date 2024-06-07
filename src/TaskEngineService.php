<?php

namespace TaskEngine\SDK;

use TaskEngine\SDK\Services\CreateTask;

class TaskEngineService {
    public static function createTaskQuery()
    {
        return new CreateTask();
    }
}
