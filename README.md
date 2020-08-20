# composer-php-api-params-md5-signature
With this package, we can easily generate &amp; verify signature when dealing with php api development. 此组件可用于 php api 开发工作中的接口参数签名生成与验证。
## Usage 用法
- import this package 引入此包
```shell
composer require mfy/api-params-md5-signature
```
- Generate signature 生成签名
```php
<?php
use ApiSignature;
use ApiSignatureException;

try {
    $params = ['a'=> $a, 'b'=> $b, ...];                                    
    $apiSignature = new ApiSignature(YOUR_SECRET, $params);
    $params['sign'] = $apiSignature->generate(); 
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