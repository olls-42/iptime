<?php

require  __DIR__  . "/../vendor/autoload.php";

$dt = new DateTimeImmutable("2000-00-00 00:00:00");
echo "localhost: ", $dt->format("Y-m-d H:i:s P"), PHP_EOL;

$dt = new DateTimeImmutable("2000-00-00 00:00:00", new DateTimeZone("UTC"));
echo "UTC: ", $dt->format("Y-m-d H:i:s P"), PHP_EOL;

try { 

    $ip = "1.1.1.1";
    // or use on host with public ip
    $ip = (new \IpTime\HostIpAddress())->getIp();

    // use for some IP
    $timezone = \IpTime\GeoIpFetcher::fetch("1.1.1.1", true);

} catch(Exception $e) {
    exit($e->message());
}

// use custom DateTimeZone
$dt = new DateTimeImmutable("2000-00-00 00:00:00", new DateTimeZone($timezone));
echo $timezone, " ", $dt->format("Y-m-d H:i:s P"), PHP_EOL;

// or set as default timezone
date_default_timezone_set($timezone);
$dt = new DateTimeImmutable("2000-00-00 00:00:00");
echo "localhost: ", $dt->format("Y-m-d H:i:s P"), PHP_EOL;