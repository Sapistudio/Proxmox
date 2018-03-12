Proxmox API Client
====================

This **PHP 5.4+** library allows you to interact with your Proxmox server via API.

> If you find any errors, typos or you detect that something is not working as expected please open an [issue](https://github.com/ZzAntares/ProxmoxVE/issues/new) or tweetme [@ZzAntares](https://twitter.com/ZzAntares). I'll try to release a fix asap.

Installation
------------

Recommended installation is using [Composer], if you do not have [Composer] what are you waiting?

In the root of your project execute the following:

```sh
$ composer require sapistudio/proxmox
```


Usage
-----

```php
<?php

// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace
use SapiStudio\Proxmox\Handler;

// Create your credentials array
$credentials = [
    'hostname' => 'proxmox.server.com',  // Also can be an IP
    'username' => 'root',
    'password' => 'secret',
];

// realm and port defaults to 'pam' and '8006' but you can specify them like so
$credentials = [
    'hostname' => 'proxmox.server.com',
    'username' => 'root',
    'password' => 'secret',
    'realm' => 'pve',
    'port' => '9009',
];

// Then simply pass your credentials when creating the API client object.
$proxmox = Handler::Nodes($credentials);

$allNodes = $proxmox->listNodes();

print_r($allNodes);
```


Sample output:

```php
Array
(
    [data] => Array
        (
            [0] => Array
                (
                    [disk] => 2539465464
                    [cpu] => 0.031314446882002
                    [maxdisk] => 30805066770
                    [maxmem] => 175168446464
                    [node] => mynode1
                    [maxcpu] => 24
                    [level] =>
                    [uptime] => 139376
                    [id] => node/mynode1
                    [type] => node
                    [mem] => 20601992182
                )

        )

)
```

License
-------

This project is released under the MIT License. See the bundled [LICENSE] file for details.
