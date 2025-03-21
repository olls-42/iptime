<?php declare(strict_types=1);

namespace IpTime;

interface GeoIpProviderInterface {
    function makeRequest(): void;
    function getTimezone(): string;
}