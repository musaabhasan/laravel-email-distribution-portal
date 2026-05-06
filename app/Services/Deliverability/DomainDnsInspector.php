<?php

namespace App\Services\Deliverability;

class DomainDnsInspector
{
    public function inspect(string $domain): array
    {
        $findings = [];
        $spf = $this->hasTxtRecord($domain, 'v=spf1');
        $dmarc = $this->hasTxtRecord("_dmarc.{$domain}", 'v=DMARC1');
        $dkim = null;

        if (! $spf) {
            $findings[] = 'SPF record was not detected.';
        }

        if (! $dmarc) {
            $findings[] = 'DMARC record was not detected.';
        }

        $score = ($spf ? 40 : 0) + ($dmarc ? 40 : 0) + 20;

        return [
            'domain' => $domain,
            'spf_pass' => $spf,
            'dkim_pass' => $dkim,
            'dmarc_pass' => $dmarc,
            'score' => $score,
            'findings' => $findings,
        ];
    }

    private function hasTxtRecord(string $domain, string $needle): bool
    {
        $records = dns_get_record($domain, DNS_TXT) ?: [];

        foreach ($records as $record) {
            $txt = is_array($record['txt'] ?? null) ? implode('', $record['txt']) : ($record['txt'] ?? '');
            if (str_contains($txt, $needle)) {
                return true;
            }
        }

        return false;
    }
}
