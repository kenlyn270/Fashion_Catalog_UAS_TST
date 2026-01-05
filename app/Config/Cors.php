<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public array $allowedOrigins = ['*'];

    public array $allowedHeaders = [
        'Content-Type',
        'Authorization',
        'X-API-KEY',
    ];

    public array $allowedMethods = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS',
    ];

    public bool $supportsCredentials = false;
}
