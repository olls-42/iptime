<?php declare(strict_types=1);

namespace IpTime;

/**
 * GeoIpFetcher an 
 */
class GeoIpFetcher  {
    private const _namespace = "/GeoIpProviders";
    private const _tmp_filename = "/timezone.dat"; 
    /** @var $providers GeoIpProviderInterface[] */
    private array $providers = [];
    private string $ip;
    private static $instance = null;
    private ?string $timezone = null;
    
    public static function fetch($ip, bool $force = false) {
        if (static::$instance === null || $force) {
            static::$instance = new self($ip, $force);
        }

        return static::$instance->getTimezone();
    }

    public function getTimezone(): ?string {
        return $this->timezone;
    }

    private function __construct(string $ip, bool $force) {
        $this->ip = $ip;
        $tmp_filename = sys_get_temp_dir() . self::_tmp_filename;

        if (file_exists($tmp_filename) && $force === false) {
            $this->timezone = file_get_contents($tmp_filename);
        } else {
            $this->_scan_providers();
            $this->_call_providers();
        }
    }

    /**
     * Call available GeoIp providers, and use first valid timezone value
     */
    private function _call_providers() {
        foreach($this->providers as $provider) {
            try {
                $provider->makeRequest();
            } catch(\Exception $e) {
                // echo to logger $e->getMessage(); ?
                continue;
            }

            $this->timezone = $provider->getTimezone();

            if ($this->isValid()) {
                file_put_contents(sys_get_temp_dir() . self::_tmp_filename, $this->timezone);

                return;
            }
        }

        throw new RuntimeException("All providers fails, can't set timezone");
    }

    /**
     * Very simple validator for timezone value
     */
    private function isValid() {
        if (strlen($this->timezone)) {
            return true;
        }

        return false;
    }

    /**
     * Collect available providers
     * This solution maybe can be improved via abstract class 
     */
    private function _scan_providers() {
        $files = scandir(__DIR__ . self::_namespace, SCANDIR_SORT_ASCENDING);
        foreach($files as $class){
            if(strpos($class, '.php')){
                $_class = "\IpTime\GeoIpProviders\\" . substr($class, 0, -4);
                array_push($this->providers, new $_class($this->ip));
            }
        }
    }

}