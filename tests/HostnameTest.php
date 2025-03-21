<?php declare(strict_types=1);

namespace IpTime;

use PHPUnit\Framework\TestCase;

final class HostnameTest extends TestCase
{
    public function testCanGetIpFromHostname(): void
    {
        $hostIp = (new HostIpAddress())->getIp();

        $this->assertFalse($hostIp === HostIpAddress::LOCALHOST);
    }
}


