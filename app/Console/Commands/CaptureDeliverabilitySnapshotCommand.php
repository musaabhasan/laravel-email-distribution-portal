<?php

namespace App\Console\Commands;

use App\Models\DeliverabilitySnapshot;
use App\Services\Deliverability\DomainDnsInspector;
use Illuminate\Console\Command;

class CaptureDeliverabilitySnapshotCommand extends Command
{
    protected $signature = 'mail:deliverability-snapshot {domain? : Optional domain to inspect}';

    protected $description = 'Capture SPF, DKIM, and DMARC evidence for configured sender domains.';

    public function handle(DomainDnsInspector $inspector): int
    {
        $domains = $this->argument('domain')
            ? [$this->argument('domain')]
            : config('mailflow.allowed_from_domains', []);

        foreach ($domains as $domain) {
            $result = $inspector->inspect($domain);

            DeliverabilitySnapshot::query()->create([
                'domain' => $domain,
                'spf_pass' => $result['spf_pass'],
                'dkim_pass' => (bool) $result['dkim_pass'],
                'dmarc_pass' => $result['dmarc_pass'],
                'score' => $result['score'],
                'findings' => $result['findings'],
                'checked_at' => now(),
            ]);

            $this->line("{$domain}: {$result['score']}");
        }

        return self::SUCCESS;
    }
}
