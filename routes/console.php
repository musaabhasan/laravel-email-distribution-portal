<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('portal:about', function (): void {
    $this->info('Secure Laravel email distribution portal with throttled SMTP delivery and audit logging.');
});
