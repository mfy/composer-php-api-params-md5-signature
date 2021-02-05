# composer-php-api-params-md5-signature
With this package, we can easily generate &amp; verify signature when dealing with php api development. 此组件可用于 php api 开发工作中的接口参数签名生成与验证。
## Usage 用法
- import this package 引入此包
```shell
composer require mfy/api-params-md5-signature
```
- initialize 初始化
```php
use  mfy\ApiSignature;
// you can modify the config array if necessary.
// 必要时可以修改如下配置
$config = [
    'sign_key' => 'sign', // the key's name of signature. 请求参数中签名参数名
    'secret_key' => 'appSecret', // the key's name of appSecret. 请求参数中密钥参数名
    'timestamp_key' => 'timestamp', // the key's name of timestamp. 请求参数中的时间戳参数名
    'timeout_limit' => 0,  //the maximum time gap between client and server, by seconds. 0 means no limit. 客户端与服务端之间最大的时间间隔，单位秒，为0时表示不限制
];
$apiSignature = new ApiSignature(YOUR_SECRET, $_GET, $config);
```
- Generate signature 生成签名
```php
<?php
use  mfy\ApiSignature;
use  mfy\ApiSignatureException;

try {
    $params = ['a'=> $a, 'b'=> $b, ...];                                    
    $apiSignature = new ApiSignature(YOUR_SECRET, $params);
    $params['sign'] = $apiSignature->generate(); 
    // or you can directly get query param array
    $query_params = $apiSignature->generateQueryArray();
    // continue ...
} catch (ApiSignatureException $e) {
    // do somethings
}
```
- Verify signature 验证签名
```php
<?php
use ApiSignature;
use ApiSignatureException;

try {
    $apiSignature = new ApiSignature(YOUR_SECRET, $_GET);
    $apiSignature->verify();
    // continue ...
} catch (ApiSignatureException $e) {
    // do somethings
}
```