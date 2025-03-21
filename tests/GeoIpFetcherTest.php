<?php declare(strict_types=1);

namespace IpTime;

use PHPUnit\Framework\TestCase;

final class GeoIpFetcherTest extends TestCase
{
    /**
     * @todo not valid test, maybe can we use some static data for an host?
     */
    public function testRequestFirstAvailableProviderShouldReturnValidTimezone(): void
    {
        $timezone = GeoIpFetcher::fetch("24.48.0.1");

        $this->assertTrue($timezone === "America/Toronto");
    }

}


