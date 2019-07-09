<?php


class Service {

    private $name = "";
    private $port = -1;
    private $timeout = -1;
    private $score = 100;
    private $description = "";

    public function __construct($name, $port, $timeout, $score, $description) {
        $this->name = $name;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->score = $score;
        $this->description = $description;

    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPort(): int {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getScore(): int {
        return $this->score;
    }

    /**
     * @return int
     */
    public function getTimeout(): int {
        return $this->timeout;
    }

    public function checkStatus($ip) {


//        fsockopen('[2a03:b0c0:3:d0::14a:e001]', 80, $return_error_number, $return_error, 10)
//        fsockopen('123.122.122.122', 80, $return_error_number, $return_error, 10)
        if($fp = fsockopen($ip,$this->port,$errCode,$errStr,$this->timeout)){
            // It worked
            fclose($fp);
            return true;
        } else {
            if($errStr == "Invalid argument") {
                echo "Note: Invalid argument supplied. Please fix this entry (mostly wrong ipv6 format) <br>";
            }
            // It didn't work
            return false;
        }
    }
}