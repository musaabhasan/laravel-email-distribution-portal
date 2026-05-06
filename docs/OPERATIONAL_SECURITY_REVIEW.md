# Operational Security Review Checklist

This checklist supports recurring assurance reviews for the Laravel Email Distribution Portal. It is designed for teams that need defensible bulk email operations, clear accountability, and a practical way to verify that delivery speed does not weaken privacy, security, or domain reputation.

## Review Cadence

| Area | Recommended cadence | Evidence to retain |
| --- | --- | --- |
| Access control | Monthly | Administrator list, MFA status, privileged-role changes |
| SMTP and DNS posture | Monthly | SPF, DKIM, DMARC, MX, TLS, and bounce-domain snapshots |
| Suppression governance | Monthly | Unsubscribe, bounce, complaint, and manual-block exports |
| Broadcast approvals | Per campaign | Approval timestamp, approver, audience, template version |
| Queue reliability | Per release | Failed job review, retry pattern, rate-limit validation |
| Audit logging | Quarterly | Sampled administrative events and retention verification |
| Incident readiness | Quarterly | Runbook drill notes and contact-list verification |

## Pre-Production Review

- Confirm `APP_ENV=production`, `APP_DEBUG=false`, and a strong `APP_KEY`.
- Store database, SMTP, and application secrets outside the repository.
- Enforce HTTPS at the edge and reject plaintext administrative access.
- Run migrations against a staging clone before production deployment.
- Confirm scheduler and queue workers run under a restricted service account.
- Verify that log paths and storage directories are not web-accessible.
- Confirm backups cover database records, suppression lists, and audit logs.
- Test restore procedures before the first production broadcast.

## Identity And Access

- Require MFA for all administrative accounts.
- Assign the minimum role needed for recipient, template, broadcast, and compliance tasks.
- Review dormant accounts and remove access that is no longer required.
- Separate operational senders from platform administrators where possible.
- Log authentication events, role changes, template approvals, and broadcast state changes.
- Use named accounts rather than shared administrator credentials.

## Recipient Data Protection

- Collect only fields needed for segmentation, personalization, compliance, and delivery.
- Validate imported addresses before queueing a broadcast.
- Keep consent source, consent timestamp, and suppression reason available for audit.
- Respect suppression records before every send attempt.
- Restrict exports to authorized roles and record the export purpose.
- Retain recipient data according to an approved records schedule.
- Remove stale or unverified recipient records from active segments.

## Template And Content Assurance

- Use approved templates for official campaigns.
- Check unsubscribe links, organization identity, sender address, and reply-to handling.
- Validate all personalization fields before campaign approval.
- Preview HTML and plaintext output before scheduling.
- Avoid URL shorteners for institutional communications.
- Keep template revisions linked to the broadcast that used them.

## Delivery Controls

- Keep the rolling send window aligned with SMTP provider limits.
- Use database-backed throttling rather than process-local counters.
- Monitor bounce rate, complaint rate, block rate, and delivery latency.
- Pause campaigns when SMTP responses show reputation risk or authentication failure.
- Avoid repeated retries to invalid, suppressed, or hard-bounced addresses.
- Track message identifiers and SMTP response codes for post-campaign review.

## Deliverability And Domain Reputation

- Verify SPF includes the authorized sending infrastructure.
- Verify DKIM signing is active for outbound messages.
- Maintain a DMARC policy with reporting enabled.
- Monitor DNS and reputation changes before high-volume campaigns.
- Keep sender identity consistent across envelope-from, header-from, and reply-to values.
- Review blocklist indicators when delivery anomalies appear.

## Incident Response

- Define clear stop-send authority for suspicious campaigns or account misuse.
- Preserve delivery logs, approval records, queue state, and SMTP responses.
- Disable affected accounts or credentials before investigation expands.
- Revoke exposed SMTP credentials and rotate application secrets when needed.
- Notify impacted stakeholders using an approved communications path.
- Record root cause, corrective actions, and control improvements.

## Release Readiness

- Run the automated test suite before deployment.
- Review migration impact on large recipient, queue, and log tables.
- Confirm rollback steps for application and database changes.
- Verify scheduler frequency after deployment.
- Validate that audit events continue to be written after release.
- Perform a low-volume test broadcast before operational use.

## Audit Questions

- Can the team prove who approved each broadcast and when?
- Can the team prove that suppressed recipients were excluded?
- Can the team explain why a recipient received a message?
- Can the team reconstruct SMTP outcomes for each broadcast?
- Can the team pause or stop sending within the active delivery window?
- Can the team restore service without losing suppression or audit history?
