<?php
// this script is meant to be configured ONCE for ALL servers of a cluster and then deployed using the exact same settings for each monitor server to get the same results, the only difference should be in the config.php file setting a correct MYIPS
//ini_set("display_errors", 1);
//ini_set("display_startup_errors", 1);
//ini_set("error_reporting", E_ALL);

// See https://www.netcup-wiki.de/wiki/Netcup_VCP_Webservice
require_once './config.php';
require_once './VcpWebServiceEndUser.class.php';
require_once './ServiceHost.class.php';


$scpapi = new VcpWebServiceEndUser(WSUSER,WSPASS);

$selected = mt_rand(1,5);
$i = 1;
$servers = $scpapi->getVServers();
foreach ($servers as $servername) {
    if($selected == $i) {
//        var_dump($scpapi->changeIPRouting(FAILOVERIPV4,FAILOVERIPV4MASK,$servername,""));
    }
    $interfaces = $scpapi->getVServerInformation($servername)->serverInterfaces;
    foreach ($interfaces as $interface) {
        if(isset($interface->ipv4IP)) {
            var_dump($interface);
        }
    }
//    var_dump($scpapi->getVServerInformation($servername));
//    var_dump($scpapi->getVServerIPs($servername));
    echo "<br>";
    $i++;
}



die("Done");

/**
 * !!!! static config do not edit between hosts of the same cluster !!!!
 * !!!! should be configured once and the rolled out to all monitors !!!!
 */

$ip_node1 = array("12.12.11.22", "[fe80:1111:2132:3222:3323:2222:0001:0001]");
$ip_node2 = array("8.8.8.8");
$ip_node3 = array("193.31.25.154");
$ip_failover = array("8.8.4.4", "[5555:4444:1222:2322:2222:2322:2222:5554]");


$host_failover = new ServiceHost("Failover Main Host ", $ip_failover);
$host1 = new ServiceHost("Host 1", $ip_node1);
$host2 = new ServiceHost("Host 2", $ip_node2);
$host3 = new ServiceHost("Host 3", $ip_node3);

$service1 = new Service("Apache", 80, 1, 200, "Main Apache non SSL");
$service2 = new Service("Apache", 443, 1, 200, "Main Apache SSL");
$service4 = new Service("Teamspeak 3 Server (serverquery)", 10011, 1, 5, "Main Teamspeak 3 Server");

$host1->addService($service1);
$host1->addService($service2);

$host2->addService($service4);


$host3->addService($service1);
$host3->addService($service4);

$host_failover->addService($service1);


$hosts = array($host1, $host2, $host3);

// check current failover
$host_failover->checkServiceHost();
$better_host_found = false;
$best_new_host = $host_failover;
// check all alternatives
foreach ($hosts as $host) {
    $host->checkServiceHost();
    if ($best_new_host->getHostScore() < $host->getHostScore()) {
        // TODO add function for selfcheck here
        $host_is_myself = checkIfMyself($host->getIpA());
        // dont set myself as best host
        if ($host_is_myself === true) {
            continue;
        }
        $best_new_host = $host;
        $better_host_found = true;
    }
}


function checkIfMyself($ip_a) {
    foreach ($ip_a as $ip) {
        if (in_array($ip, MYIPS) === true) {
            // note we are ourself
            return true;
        }
    }
    return false;
}

if ($better_host_found === true) {
    echo "Found better host which is not myself. <br>";
    echo "Switching FailoverIP to: <br>";
    $name = $best_new_host->getHostname();
    echo "Name: $name<br>";

    // do SCP API Call here
} else {
    echo "Current failover is the best host. <br>";
}






