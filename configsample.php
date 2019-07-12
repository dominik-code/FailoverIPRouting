<?php
define("WSUSER","");
define("WSPASS","");
// enter own ips here so we know what host to ignore if we find localhost a better master as current (possible network error)
define("MYIPS",array("1.2.3.4","[fe00::333::22:22:0011:0001]"));


define("FAILOVERIPV4","1.2.3.4");
define("FAILOVERIPV4MASK","32");
define("FAILOVERIPV6","1223:1234:3233:2223::"); //note :: at end
define("FAILOVERIPV6MASK","64");