# Timezone by Host IP Address 

Composer package that will return the current time in the server's time zone. Based on the server's IP. 

Please use carefully.

Publish to packagist is useless, because project not ready for community standards:
But possible to use as vcs dependency, please check: https://getcomposer.org/doc/05-repositories.md#vcs

Please refer source of .bin/example.php

For example it is default values for new Datetime().

- localhost: 2000-00-00 00:00:00 +00:00
- UTC: 2000-00-00 00:00:00 +00:00

IpApiProvider has simple method that make Curl request to External GeoIP Service 

'''

    $ip = "1.1.1.1";
    // or use on host with public ip
    $ip = (new \IpTime\HostIpAddress())->getIp();

    // use for some IP
    $timezone = \IpTime\GeoIpFetcher::fetch("1.1.1.1", true);

    // America/Toronto 2000-00-00 00:00:00 -04:00
'''

