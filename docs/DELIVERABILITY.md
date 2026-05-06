# Deliverability

## Sender Identity

Before production sending, configure and verify:

- SPF alignment for the approved SMTP relay.
- DKIM signing for all production sender domains.
- DMARC policy with monitored aggregate reports.
- Reverse DNS and HELO/EHLO alignment where the SMTP provider allows it.
- A stable `MAIL_FROM_ADDRESS` from an approved domain.

## Throttling

The default limit is 400 successful sends per 60-minute rolling window:

```env
BROADCAST_BATCH_LIMIT=400
BROADCAST_WINDOW_MINUTES=60
```

The sender queries `delivery_logs` for successful messages in the active window and sends only the remaining allowance. This makes the limit resilient to cron restarts, PHP process failures, or manual retries.

## Suppression Hygiene

Suppress recipients when:

- They unsubscribe.
- A hard bounce is received.
- A complaint is reported.
- A compliance officer or administrator blocks the address.
- A source system marks the address inactive.

## Recommended Operating Cadence

- Run deliverability snapshots hourly.
- Review failed and deferred logs daily during warm-up.
- Keep complaint rates and bounce rates under provider thresholds.
- Start with smaller batches for new domains, then increase after stable engagement.
- Keep content useful, expected, and aligned with the recipient consent source.
