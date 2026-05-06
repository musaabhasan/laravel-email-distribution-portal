# Operations Runbook

## Cron

Use the Laravel scheduler:

```bash
* * * * * cd /var/www/laravel-email-distribution-portal && php artisan schedule:run >> /dev/null 2>&1
```

## Queue Worker

Run queue workers under a process manager:

```bash
php artisan queue:work --queue=mail,default --tries=3 --timeout=120 --sleep=3
```

## Manual Commands

Queue due broadcasts:

```bash
php artisan mail:dispatch-due-broadcasts
```

Check allowance:

```bash
php artisan mail:send-broadcast-batch --dry-run
```

Send next batch:

```bash
php artisan mail:send-broadcast-batch
```

Capture DNS evidence:

```bash
php artisan mail:deliverability-snapshot example.org
```

## Recovery

If sending is interrupted:

1. Confirm SMTP service health.
2. Review `delivery_logs` for failure patterns.
3. Confirm `broadcast_recipients` rows remain `pending` with valid `available_at` values.
4. Run `php artisan mail:send-broadcast-batch --dry-run`.
5. Resume the scheduler or run the batch command manually.

The database queue model avoids duplicate recipient queueing through the `broadcast_id` and `recipient_id` unique index.
