<?php

namespace mfy;

class ApiSignature
{
    private $config = [
        'sign_key' => 'sign',
        'secret_key' => 'appSecret',
        'timestamp_key' => 'timestamp',
        'timeout_limit' => 0,
    ];

    private $secret = '';
    private $params = [];


    public function __construct($secret = '', $params = [], $config = [])
    {
        if (empty($secret) || empty($params)) {
            throw new ApiSignatureException('secret/params could not be empty', 10001);
        }
        $this->secret = $secret;
        $this->params = $params;
        $this->config = array_merge($this->config, $config);
    }

    public function generate($get_query_array = false)
    {
        $params = array_diff_key($this->params, array_flip([$this->config['sign_key']]));
        if ((int)$this->config['timeout_limit'] !== 0 && !isset($params[$this->config['timestamp_key']])) {
            // auto add timestamp if not exist && timeout configured
            $params[$this->config['timestamp_key']] = time();
        }
        $params[$this->config['secret_key']] = $this->secret;
        ksort($params);
        $outer_params = http_build_query($params);
        $params[$this->config['sign_key']] = md5($outer_params);
        unset($params[$this->config['secret_key']]);
        return $get_query_array ? $params : $params[$this->config['sign_key']];
    }

    public function generateQueryArray()
    {
        return $this->generate(true);
    }

    /**
     * @return bool
     * @throws ApiSignatureException
     */
    public function verify(): bool
    {
        if ((int)$this->config['timeout_limit'] !== 0) {
            if (abs(time() - $this->params[$this->config['timestamp_key']] ?? 0) > $this->config['timeout_limit']) {
                throw new ApiSignatureException('timeout (' . $this->config['timeout_limit'] . ' s) limit, check server/client time', 10002);
            }
        }
        if (($this->params[$this->config['sign_key']] ?? '') !== $this->generate()) {
            throw new ApiSignatureException('signature verify failed', 10003);
        }
        return true;
    }
}
