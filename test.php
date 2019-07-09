<?php
// this script is meant to be configured ONCE for ALL servers of a cluster and then deployed using the exact same settings for each monitor server to get the same results, the only difference should be in the config.php file setting a correct MYIPS
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
ini_set("error_reporting", E_ALL);



// See https://www.netcup-wiki.de/wiki/Netcup_VCP_Webservice
require_once './config.php';
require_once './VcpWebServiceEndUser.class.php';
require_once './ServiceHost.class.php';

$ip_a1 = array("12.12.11.22", "[fe80::232:322:333:2222:0001:0001]");
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

// check all alternatives
foreach ($hosts as $host) {
    $host->checkServiceHost();
}

// check if current master is not localhost (we dont want to ignore network routing problems)
$isfailover = true;

// we can check this if one of the localhost ips is in definded MYIPS array
if (!$isfailover) {

    // if betterhost found -> switch routing

}
// take actions based on results only if a better host is found otherwise keep current failover host
// select the host with the best online services to be the new master







