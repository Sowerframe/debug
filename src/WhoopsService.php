<?php declare(strict_types = 1);


namespace sower\Whoops;

use sower\Service;
use sower\Whoops\Hander\WhoopsExceptionHandle;

class WhoopsService extends Service
{
    public function register()
    {
        $this->app->bind('sower\exception\Handle', WhoopsExceptionHandle::class);
    }
}
