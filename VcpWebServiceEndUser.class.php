<?php

ini_set('default_socket_timeout', 360);

class VcpWebServiceEndUser {

    private $wsdl_url = 'https://www.servercontrolpanel.de/WSEndUser?wsdl';
    private $soap_client;
    private $loginname;
    private $password;

    public function getArrayFrom2DWebServiceStringArray($webServiceResultArray) {
        $phpArray = array();
        foreach ($webServiceResultArray->return as $globalArray) {
            $phpArray[$globalArray->item[0]] = $globalArray->item[1];
        }
        return $phpArray;
    }

    function __construct($loginname, $password) {
        $this->loginname = $loginname;
        $this->password = $password;
        $this->soap_client = new SOAPClient($this->wsdl_url, array('cache_wsdl' => 0));

        $functions = $this->soap_client->__getFunctions();
        foreach ($functions as $function) {
            var_dump($function);
            echo "<br>";
        }
        // TODO error if no connection to soap server ...
    }

    /**
     *
     * @param String $vserverName needed
     * @return String if action deleteVServer successfully started return actionId else return errorMessage
     */
    public function getVServers() {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
            );

            $getVServerResult = $this->soap_client->getVServers($params);

            return $getVServerResult->return;

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String
     */
    public function getVServerState($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $startVServerResult = $this->soap_client->getVServerState($params);

            return $startVServerResult->return;

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String
     */
    public function getVServerLoad($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $startVServerResult = $this->soap_client->getVServerLoad($params);

            return $startVServerResult->return;

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String
     */
    public function getVServerUptime($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $startVServerResult = $this->soap_client->getVServerUptime($params);

            return $startVServerResult->return;

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String[]
     */
    public function getVServerIPs($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $startVServerResult = $this->soap_client->getVServerIPs($params);

            return $startVServerResult->return;

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String if action deleteVServer successfully started return actionId else return errorMessage
     */
    public function startVServer($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $startVServerResult = $this->soap_client->startVServer($params);

            if ($startVServerResult->return->success) {
                return $startVServerResult->return->exceptionMessage;
            } else {
                if ($startVServerResult->return->exceptionMessage != NULL) {
                    return $startVServerResult->return->exceptionMessage;
                } else {
                    return "undefined error";
                }
            }

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String if action deleteVServer successfully started return actionId else return errorMessage
     */
    public function stopVServer($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            $stopVServerResult = $this->soap_client->stopVServer($params);

            if ($stopVServerResult->return->success) {
                return $stopVServerResult->return->exceptionMessage;
            } else {
                if ($stopVServerResult->return->exceptionMessage != NULL) {
                    return $stopVServerResult->return->exceptionMessage;
                } else {
                    return "undefined error";
                }
            }

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String if action deleteVServer successfully started return actionId else return errorMessage
     */
    public function getFirewall($vserverName) {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName
            );

            return $this->soap_client->getFirewall($params);

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    /**
     *
     * @param String $vserverName needed
     * @return String if action deleteVServer successfully started return actionId else return errorMessage
     */
    public function addFirewallRule($vserverName, $direction, $proto, $srcip, $srcport, $srcportrange, $destip, $destport, $destportrange, $match, $matchvalue, $target) {

        try {

            $rule_params = array(
                'direction' => $direction, // string needed
                'proto' => $proto, // string needed
                'srcIP' => $srcip,
                'srcPort' => $srcport,
                'destIP' => $destip,
                'destPort' => $destport,
                'match' => $match,
                'matchValue' => $matchvalue,
                'srcPortRange' => $srcportrange,
                'destPortRange' => $destportrange,
                'target' => $target,
                'valid' => false,
                'id' => 0,
            );

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vserverName' => $vserverName,
                'rule' => array($rule_params),
            );

            return $this->soap_client->addFirewallRule($params);

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }

    public function getVServerInformation($vserverName, $language = "DE") {

        try {

            $params = array(
                'loginName' => $this->loginname,
                'password' => $this->password,
                'vservername' => $vserverName,
                'language' => $language,
            );

            return $this->soap_client->getVServerInformation($params);

        } catch (Exception $e) {
            return "Exception occured: " . $e->getMessage();
        }
    }
}