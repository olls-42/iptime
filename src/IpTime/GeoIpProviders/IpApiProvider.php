<?php declare(strict_types=1);

namespace IpTime\GeoIpProviders;

/**
 * See http://ip-api.com for service provider documentation
 */
class IpApiProvider implements \IpTime\GeoIpProviderInterface {
  
    private const URL = "http://ip-api.com/json/";
    private string $ipAddress;
    private const TIMEOUT = 200;
    private ?array $data = null;

    public function __construct(string $ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    public function makeRequest(): void {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, self::URL . $this->ipAddress);
        // dev: curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::TIMEOUT);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode !== 200) {
            throw new RuntimeException("Can't make GET request to: " . self::URL);
        }
        if (!strlen($body)) {
            throw new RuntimeException("Empty response from: " . self::URL);
        }

        $this->data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        if (!array_key_exists("timezone", $this->data)) {
            throw new RuntimeException("Can't convert IP to Timezone");
        }

    }

    public function getTimezone(): string {
        return $this->data['timezone'];
    }
}