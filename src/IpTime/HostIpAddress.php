<?php declare(strict_types=1);

namespace IpTime;

/**
 * Class allow get IP Address from the host machine network interface
 * 
 * Solution created by suggestion from topic:
 * https://stackoverflow.com/questions/13322485/how-to-get-the-primary-ip-address-of-the-local-machine-on-linux-and-os-x
 * https://stackoverflow.com/questions/5284147/validating-ipv4-addresses-with-regexp
 * 
 */
class HostIpAddress {

    const LOCALHOST = "127.0.0.1";
    const IPv4 = "/((25[0-5]|(2[0-4]|1\d|[1-9]|)\d)\.?\b){4}/";
    const IPv6 = ""; // We not need validate address internally
    //

    public function __construct (
        private ?string $ip = null
    ) {
        $this->ip = self::LOCALHOST;

        if (array_key_exists('SERVER_ADDR', $_SERVER)) {
            $this->ip = $_SERVER['SERVER_ADDR'];
        } else {
            //  not available for cli, then let's parse hostname -I
            $this->parseHostname();
        }
    }


    public function getIp(): string
    {
        return $this->ip;
    }
    
    /**
     * Try to get an ip addresses for the host
     */
    private function parseHostname(): void
    {
        $output=null;
        $returnCode=null;

        exec('hostname -I', $output, $returnCode);

        if ($returnCode !== 0) {
            throw new RuntimeException("Can't exec hostname application");
        }

        // let's check parsed data, is still an IP address
        $matches = null;
        preg_match(self::IPv4, $output[0], $matches);

        if (count($matches)){
            $this->ip = $output[0];
  
            return;
        } 

        // todo add IPv6

        throw new RuntimeException("Can't get ip from hostname application");
    }

    /**
     * @todo add fallback solution
     * @not_implemented 
     */
    private function tryIfconfig(): ?string
    {
        $command = "ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'";
        $output=null;
        $returnCode=null;
        exec($command, $output, $returnCode);

        return null;
    }
}