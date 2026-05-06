<?php

namespace Tests\Unit;

use App\Services\Security\TotpService;
use Tests\TestCase;

class TotpServiceTest extends TestCase
{
    public function test_it_verifies_generated_totp_codes(): void
    {
        $service = new TotpService;
        $secret = 'JBSWY3DPEHPK3PXP';
        $slice = (int) floor(time() / 30);
        $code = $service->generate($secret, $slice);

        $this->assertTrue($service->verify($secret, $code));
        $this->assertFalse($service->verify($secret, '000000', window: 0));
    }
}
