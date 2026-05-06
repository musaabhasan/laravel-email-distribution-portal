<?php

use App\Console\Commands\CaptureDeliverabilitySnapshotCommand;
use App\Console\Commands\DispatchDueBroadcastsCommand;
use App\Console\Commands\SendBroadcastBatchCommand;
use App\Http\Middleware\AuditAdministrativeAction;
use App\Http\Middleware\RequireMfa;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'mfa' => RequireMfa::class,
            'audit.admin' => AuditAdministrativeAction::class,
        ]);
    })
    ->withCommands([
        DispatchDueBroadcastsCommand::class,
        SendBroadcastBatchCommand::class,
        CaptureDeliverabilitySnapshotCommand::class,
    ])
    ->withSchedule(function (): void {
        Schedule::command('mail:dispatch-due-broadcasts')->everyMinute()->withoutOverlapping();
        Schedule::command('mail:send-broadcast-batch')->everyThirtyMinutes()->withoutOverlapping();
        Schedule::command('mail:deliverability-snapshot')->hourly()->withoutOverlapping();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
