<?php
// this script is meant to be configured ONCE for ALL servers of a cluster and then deployed using the exact same settings for each monitor server to get the same results, the only difference should be in the config.php file setting a correct MYIPS
//ini_set("display_errors", 1);
//ini_set("display_startup_errors", 1);
//ini_set("error_reporting", E_ALL);

// See https://www.netcup-wiki.de/wiki/Netcup_VCP_Webservice
require_once './config.php';
require_once './VcpWebServiceEndUser.class.php';
require_once './ServiceHost.class.php';

/**
 * !!!! static config do not edit between hosts of the same cluster !!!!
 * !!!! should be configured once and the rolled out to all monitors !!!!
 */

$ip_a1 = array("12.12.11.22", "[fe80:1111:2132:3222:3323:2222:0001:0001]");
$ip_a2 = array("8.8.8.8");
$ip_failover = array("8.8.4.4", "[5555:4444:1222:2322:2222:2322:2222:5554]");


$host_failover = new ServiceHost("Failover Main Host ", $ip_failover);
$host1 = new ServiceHost("Host 1", $ip_a1);
$host2 = new ServiceHost("Host 2", $ip_a2);

$service1 = new Service("Apache", 80, 1, 200, "Main Apache non SSL");
$service2 = new Service("Apache", 443, 1, 200, "Main Apache SSL");
$service3 = new Service("Teamspeak 3 Server", 9987, 1, 5, "Main Teamspeak 3 Server");

$host1->addService($service1);
$host1->addService($service2);
$host1->addService($service3);

$host2->addService($service3);

$host_failover->addService($service1);


$hosts = array($host1, $host2);

// check current failover
$host_failover->checkServiceHost();
$better_host_found = false;
$best_new_host = $host_failover;
// check all alternatives
foreach ($hosts as $host) {
    $host->checkServiceHost();
    if ($best_new_host->getHostScore() < $host->getHostScore()) {
        $host_is_myself = true;
        // dont set myself as best host
        if($host_is_myself) {
            continue;
        }
        $best_new_host = $host;
        $better_host_found = true;
    }
}


function getOwnInterfaceIPs() {
    // thanks to https://stackoverflow.com/questions/5800927/how-to-identify-server-ip-address-in-php for pointing out the regex. regex was modified to match ipv4 and ipv6
    // ip a | grep -Eo 'inet(6)? (addr:)?(([0-9]*\.){3}[0-9]*|[0-9a-f:]{4}[0-9a-f:]*)' | grep -Eo '(([0-9]*\.){3}[0-9]*|[0-9a-f:]{4}[0-9a-f:]*)' | grep -v '127.0.0.1'
    // will return a list of ips (ipv4 like 8.8.8.8) or/and (ipv6 like fe0e::333:eeee:eeee:eeee

    $my_current_ip=exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
    echo $my_current_ip;
}
getOwnInterfaceIPs();

if ($better_host_found === true) {
    echo "Found better host which is not myself. <br>";
    echo "Switching FailoverIP to: <br>";
    $name = $best_new_host->getHostname();
    echo "Name: $name<br>";

    // do SCP API Call here
} else {
    echo "Current failover is the best host. <br>";
}






