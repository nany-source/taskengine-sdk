<?php

namespace TaskEngine\SDK\Services;

use TaskEngine\SDK\Libs\Client;

class CreateTask {
    private $_options;

    // 权重
    const HIGHT_PRIORITY = 3;
    const NORMAL_PRIORITY = 2;
    const LOW_PRIORITY = 1;

    /**
     * 创建任务
     * @return mixed 创建失败抛出错误, 否则返回result内的结果或返回体
     */
    public function create()
    {
        // action不能为空
        if (! isset($this->_options['action'])) {
            throw new \Exception('Action is empty');
        }

        // 发送请求
        return (new Client())->post('/api/task/create', $this->_options);
    }

    /**
     * 设置任务Action
     * @param string $actionName Action名称
     * @return CreateTask
     * @throws \Exception 
     */
    public function setAction(string $actionName)
    {
        if (empty($actionName)) {
            throw new \Exception('action name is empty');
        }
        $this->_options['action'] = $actionName;
        return $this;
    }

    /**
     * 设置任务数据
     * @param mixed $data 任务数据
     * @return CreateTask
     */
    public function setData($data)
    {
        $this->_options['data'] = $data;
        return $this;
    }

    /**
     * 设置任务唯一键条件
     * @param mixed $uniqueCond 唯一键条件
     * @return CreateTask
     * @throws \Exception 
     */
    public function setUniqueCond($uniqueCond)
    {
        if (! isset($uniqueCond)) {
            throw new \Exception('Uniquecond is empty');
        }
        $this->_options['unique'] = $uniqueCond;

        return $this;
    }

    /**
     * 设置权重
     * @param int $weight 权重 (3: 高, 2: 正常, 1: 低)
     * @return CreateTask
     * @throws \Exception 
     */
    public function setPriority(int $priority = self::NORMAL_PRIORITY)
    {
        if (! in_array($priority, [self::HIGHT_PRIORITY, self::NORMAL_PRIORITY, self::LOW_PRIORITY])) {
            throw new \Exception('Invalid priority');
        }
        $this->_options['priority'] = $priority;
        
        return $this;
    }

    /**
     * 设置TaskEngine处理完毕后接受处理后数据的回调地址
     * @param string $url 回调地址 (必须为有效的url)
     * @return CreateTask 
     * @throws Exception 
     */
    public function setCallBackUrl(string $url)
    {
        // 检查此url是否合法
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            throw new \Exception('Invalid callback url');
        }
        $this->_options['callbackUrl'] = $url;
        return $this;
    }

    /**
     * 设置回调重试次数
     * @param int $retryCount 重试次数
     * @return CreateTask
     */
    public function setCallbackRetryCount(int $retryCount)
    {
        // 如果重试次数小于等于0, 则不设置
        if ($retryCount <= 0) {
            return $this;
        }

        $this->_options['callbackRetryCount'] = $retryCount;
        return $this;
    }

    /**
     * 设置为保活任务
     * @return $this 
     */
    public function isKeepAliveTask()
    {
        $this->_options['isKeepAlive'] = true;
        return $this;
    }

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
