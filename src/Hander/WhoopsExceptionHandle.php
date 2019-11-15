<?php declare(strict_types = 1);


namespace sower\Whoops\Hander;

use sower\exception\Handle;
use sower\exception\HttpException;
use sower\exception\ValidateException;
use sower\Response;
use sower\App;

use Throwable;
use Whoops\Run;
use sower\Whoops\Whoops;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;

class WhoopsExceptionHandle extends Handle
{
    private $whoops;

    public function __construct(App $app, Run $run)
    {
        parent::__construct($app);

        $this->whoops = new Whoops($run);
    }

    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException) {
            return json($e->getError(), 422);
        }

        if ($this->app->isDebug()) {
            
            // 请求异常
            
            $this->whoops->pushHandler(new PrettyPageHandler);

            if ($e instanceof HttpException && $request->isAjax()) {
                $this->whoops->pushHandler(new JsonResponseHandler);
            }

            $content = $this->whoops->getHandleException($e);

            return Response::create(
                $content,
                $e->getStatusCode(),
                $e->getHeaders()
            );
        }

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
