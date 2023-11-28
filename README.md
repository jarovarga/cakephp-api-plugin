# CakePHP API plugin

[CakePHP 4.x Strawberry Cookbook](https://book.cakephp.org/4/en/contents.html)

**Installation:**

```php
// src/Application.php
$this->addPlugin('Api');
```
```php
// src/Application.php
$csrf = new CsrfProtectionMiddleware();
$csrf->skipCheckCallback(function ($request) {
    if ($request->getParam('plugin') === 'Api') {
        return true;
    }

    return false;
});
```
```php
// config/bootstrap.php
// @todo middleware
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Accept, Authorization, Content-Type');
```
```shell
# plugins/Api
openssl genrsa -out config/jwt.key 1024
openssl rsa -in config/jwt.key -outform PEM -pubout -out config/jwt.pem
```
```shell
composer dumpautoload
```

**Get token:**

```js
let username = "",
    password = "",
    xhr = new XMLHttpRequest();
xhr.open("post", "https://localhost/api/v1/auth/token");
xhr.setRequestHeader("Authorization", "Basic " + btoa(username + ":" + password));
xhr.onload = function() {
    if (this.status === 200) {
        console.log(this.response);
    } else {
        console.log(this.status);
    }
};
xhr.send();
```

**Get data:**

```js
let token = "",
    xhr = new XMLHttpRequest();
xhr.open("get", "https://localhost/api/v1/data");
xhr.setRequestHeader("Authorization", "Bearer" + token);
xhr.onload = function() {
    if (this.status === 200) {
        console.log(this.response);
    } else {
        console.log(this.status);
    }
};
xhr.send();
```