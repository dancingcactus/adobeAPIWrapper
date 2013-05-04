Analytics API Wrapper
--------------------

Allows you to connect to the Adobe Analytics APIs via PHP

Prerequisites
=============
cURL for PHP must be installed
I did this with 
```
sudo apt-get install php5-curl
```

Instructions for Use
====================
There is an example in the main.php that pulls the number of you have donw. Just edit credentials.inc
```
$api =new  analyticsAPI();
$api->config("username","password");
$api->invoke("Component.Method", array("name"=>"value");
```
Thats it. The config function only needs to be called once per session unless you want to use a different user. 

The api is also smart enough to figure out which endpoint you should be using so you don't have to enter an endpoint. 


Files 
=====
analyticsAPI.php - Contains the main class. This is the file you will want to use
main.php - Sample of how to use the wrapper
credentials.inc - where you set the username and password
/tests - a set of unit tests to be run by phpunit
