<?php
require_once "./Service.class.php";

class ServiceHost {

    private $services = array();
    private $hostname = "";
    private $ip_a = array();
    private $hostscore = 0;

    /**
     * ServiceHost constructor.
     * @param $nicename
     * @param array filled with ipv4 and/or ipv6
     */
    public function __construct($nicename, $ip_a) {
        $this->hostname = $nicename;
        if (count($ip_a) < 1) {
            die("Error: Please specify at least a ipv4 or a ipv6 for the host");
        }
        $this->ip_a = $ip_a;
    }

    public function addService($service) {
        $this->services[] = $service;
    }

    public function checkServiceHost() {
        $this->hostscore = 0;
        $countservices = count($this->services);
        echo "Checking $countservices services specified for this host <br>";

        foreach ($this->ip_a as $ip) {
            echo "Checking services via IP: $ip <br>";
            foreach ($this->services as $service) {
                $result = $service->checkStatus($ip);
                $name = $service->getName();
                $port = $service->getPort();
                $desc = $service->getDescription();
                $score = $service->getScore();

                $resultstring = "Service: $name ($desc) is ";
                if ($result === true) {
                    $resultstring .= "UP";
                    $this->hostscore += $score;
                } else {
                    $resultstring .= "DOWN";
                }
                $resultstring .= " via Port $port with a score of $score<br>";
                echo $resultstring;
            }
        }


        echo "Done. <br>";
        return $this->hostscore;
    }

    /**
     * @return int
     */
    public function getHostScore(): int {
        return $this->hostscore;
    }

    /**
     * @return array
     */
    public function getIpA(): array {
        return $this->ip_a;
    }

    /**
     * @return string
     */
    public function getHostname(): string {
        return $this->hostname;
    }

    /**
     * @return array
     */
    public function getServices(): array {
        return $this->services;
    }
}