# Security Controls

## Administrative Access

- Use Laravel authentication middleware for all administrative routes.
- Enable MFA for administrator and operator accounts.
- Store MFA secrets encrypted and never expose them in logs.
- Enforce least-privilege roles for operators, approvers, and administrators.
- Keep audit logging enabled for POST, PUT, PATCH, and DELETE requests.

## SMTP Secrets

- Store SMTP credentials in environment variables or a managed secret store.
- Rotate SMTP credentials after staff changes, suspected compromise, or provider migration.
- Restrict outbound SMTP traffic to approved relay hosts.
- Use TLS for SMTP and HTTPS for administrative access.

## Data Protection

- Encrypt sessions.
- Use HTTPS-only cookies in production.
- Keep recipient import files outside public storage.
- Apply data retention rules for audit logs and delivery logs.
- Protect backups with encryption and tested restore procedures.

## Abuse Prevention

- Require broadcast approval before recipient queueing.
- Use suppression lists for unsubscribes, hard bounces, complaints, and administrative blocks.
- Keep rate limits configured conservatively until domain reputation is proven.
- Do not use the platform for unsolicited email.

## Production Checklist

- `APP_DEBUG=false`
- `APP_ENV=production`
- `SESSION_SECURE_COOKIE=true`
- `SESSION_ENCRYPT=true`
- `BROADCAST_REQUIRE_APPROVAL=true`
- SMTP credentials stored outside source control
- SPF, DKIM, and DMARC configured before first production broadcast
- Queue workers supervised by systemd, Supervisor, or an equivalent process manager
