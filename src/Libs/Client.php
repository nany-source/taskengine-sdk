<?php

namespace TaskEngine\SDK\Libs;

use Exception;

class Client {
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    protected $timeout = 0;
    protected $connectTimeout = 0;
    protected $apiEndpoint;
    protected $appKey;
    protected $appSecret;

    public function __construct()
    {
        $this->apiEndpoint = config('taskengine.api_endpoint');
        $this->appKey = config('taskengine.app_id', '');
        $this->appSecret = config('taskengine.app_secret', '');
        $this->timeout = config('taskengine.timeout', 0);
        $this->connectTimeout = config('taskengine.connect_timeout', 0);
    }

    /**
     * 发送请求
     * @param string $method 请求方法
     * @param string $url 请求地址
     * @param array $params 请求参数(post下会被json_encode后发送)
     * @param array $reqheaders 请求头
     * @return mixed
     * @throws Exception 
     */
    private function request(string $method, string $url, array $params = [], array $reqheaders = [])
    {
        // 组合url
        $endpoint = rtrim(($this->apiEndpoint), '/') . '/' . ltrim($url, '/');
        // 初始化curl
        $ch = curl_init();
        // 根据请求方法设置所需的curl参数
        switch ($method) {
            case self::METHOD_GET:
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                // 存在传参则拼接到url后
                if ($params) {
                    $endpoint .= '?' . http_build_query($params);
                }
                break;
            case self::METHOD_POST:
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                $reqheaders[] = 'Content-Type: application/json';
                break;
            default:
                curl_close($ch);
                return;
        }
        // 设置通用的curl参数
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => array_merge([
                    'request-time:' . time(),
                    'app-id:' . $this->appKey,
                    'app-secret:' . $this->appSecret,
                ], $reqheaders),
            CURLOPT_URL => $endpoint, // 请求地址
            CURLOPT_CONNECTTIMEOUT => $this->connectTimeout, // 连接超时时间
            CURLOPT_TIMEOUT => $this->timeout, // 超时时间
            CURLOPT_RETURNTRANSFER => true, // 返回结果
            CURLOPT_AUTOREFERER => true, // 自动设置referer字段
            CURLOPT_FOLLOWLOCATION => true, // 自动跟踪重定向
            CURLOPT_FRESH_CONNECT => true, // 强制获取一个新的连接，替代缓存中的连接
        ]);
        // 发送请求并获取返回的数据
        $response = curl_exec($ch);
        $httpInfo = curl_getinfo($ch);
        curl_close($ch);

        // 检查请求是否成功
        if ($httpInfo['http_code'] >= 400) {
            throw new \Exception($response);
        }
        // 获取返回的数据类型
        $httpContentType = $httpInfo['content_type'];
        // 如果为json类型
        if ($httpContentType === 'application/json') {
            if ($data = json_decode($response, true)) {
                // 检查是否为return的请求体
                if(isset($data['code'])) {
                    // 如果代码不为0存在message字段
                    if ($data['code'] !== 0) {
                        // 抛出错误
                        throw new Exception($data['message'] ?? json_encode($data));
                    } else {
                        // 返回请求体中的result字段, 不存在则为整个json数据
                        return $data['result'] ?? $data;
                    }
                }
                // 否则返回json数据
                return $data;
            }
        }

        // 否则返回原始数据
        return $response;
    }

    /**
     * 发送get请求
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $headers 请求头
     * @return mixed 
     * @throws Exception 
     */
    public function get(string $url, array $params = [], array $headers = [])
    {
        return $this->request(self::METHOD_GET, $url, $params, $headers);
    }

    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $headers 请求头
     * @return mixed 
     * @throws Exception 
     */
    public function post(string $url, array $params = [], array $headers = [])
    {
        return $this->request(self::METHOD_POST, $url, $params, $headers);
    }
}
