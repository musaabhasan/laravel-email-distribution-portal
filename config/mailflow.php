<?php

return [
    'batch_limit' => (int) env('BROADCAST_BATCH_LIMIT', 400),
    'window_minutes' => (int) env('BROADCAST_WINDOW_MINUTES', 60),
    'retry_minutes' => (int) env('BROADCAST_RETRY_MINUTES', 15),
    'max_attempts' => (int) env('BROADCAST_MAX_ATTEMPTS', 3),
    'require_approval' => (bool) env('BROADCAST_REQUIRE_APPROVAL', true),
    'allowed_from_domains' => array_filter(array_map('trim', explode(',', env('BROADCAST_ALLOWED_FROM_DOMAINS', '')))),
    'require_mfa' => (bool) env('SECURITY_REQUIRE_MFA', true),
    'audit_retention_days' => (int) env('SECURITY_AUDIT_RETENTION_DAYS', 365),
    'minimum_dns_score' => (int) env('DELIVERABILITY_MIN_DNS_SCORE', 80),
];
