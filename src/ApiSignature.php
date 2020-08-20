<?php

class ApiSignature
{
    private $config = [
        'sign_key' => 'sign',
        'timestamp_key' => 'timestamp',
        'timeout_limit' => 20,
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

    public function generate()
    {
        $params = array_diff_key($this->params, array_flip([$this->config['sign_key']]));
        if (!isset($params[$this->config['timestamp_key']])) {
            // auto add timestamp if not exist
            $params[$this->config['timestamp_key']] = time();
        }
        ksort($params);
        $params = http_build_query($params);
        return md5($params . $this->secret);
    }

    public function verify()
    {
        if ((int)$this->config['timeout_limit'] !== 0) {
            if (abs(time() - $this->params[$this->config['timestamp_key']] ?? 0) > $this->config['timeout_limit']) {
                throw new ApiSignatureException('timeout limit, check server/client time', 10002);
            }
        }
        if ($this->params[$this->config['sign_key']] ?? ($this->params[$this->config['sign_key']] === $this->generate()) ?: false) {
            throw new ApiSignatureException('signature verify failed', 10003);
        }
        return true;
    }
}
