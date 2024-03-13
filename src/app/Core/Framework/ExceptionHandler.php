<?php

namespace App\Core\Framework;

use Exception;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExceptionHandler extends Handler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[] $dontReport
     */
    protected $dontReport = [];
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'password',
        'password_confirmation'
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(static fn(Exception $e) => DB::rollBack());
    }

    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }
}
