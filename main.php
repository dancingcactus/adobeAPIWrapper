<?php 
//Include for the analyticsAPI class
require("analyticsAPI.php");
//Include for the username and password
require("credentials.inc");

echo "initializing API Class \n";
$api = new analyticsAPI();

echo "configuring the Username and Password \n";
$api->config($username, $password);

echo "Trying an API Call \n";
echo $api->invoke("Company.GetTokenCount",array("company"=>"Justin Grover"));
echo "\n";
?>