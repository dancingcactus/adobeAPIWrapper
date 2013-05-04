<?php
/**
* Contains the AdobeAnalyticsAPI class used to interact with the Adobe Reporting API
* @author Jusitn Grover
* @version 1.0
* @package AdobeAPI
*/
/**
* Interface for the Adobe Reporting API
* 
* @package AdobeAPI
*/	
Class analyticsAPI
	{

		private $username = "";
		private $password = "";
		private $endpoint = "";
		private $defaultEndpoint = "https://api.omniture.com/admin/1.3/rest/";
		private $companyEndpoint = "";
		
		/**
		* Returns the username
		* @return string
		*/
		function getUsername()
		{
			return $this->username;
		}
		
		/**
		* Sets the username
		* @returns boolean will return false if no endpoint can be determined. This usually means that the username is invalid
		*/
		function setUsername($username)
		{
			$this->username = $username;
			$endpoint = $this->getEndpoint($username);
			if (strpos($endpoint, "Invalid company specified.") == false)
			{
				$this->companyEndpoint = $endpoint;
				return true;		
			}
			else
				return false;
		}
		
		/**
		* Returns the password (Shared Secret)
		* @return string
		*/
		function getPassword()
		{
			return $this->password;
		}
		
		/**
		* Sets the password (Shared Secret)
		* @returns null
		*/
		function setPassword($password)
		{
			$this->password = $password;
			return true;
		}
		
		/**
		* Sets the username and password. 
		*
		* This method only needs to be called once since it will store the username and password in the object. The only time you would need to call it again 
		* @returns boolean
		*/
		function config($username, $password)
		{
			$validUser = $this->setUsername($username);
			$this->setPassword($password);
			return $validUser;
		}
		
		/**
		* Figures out what the endpoint is for a particular user
		* @return boolean|string
		*/
		private function getEndpoint($username)
		{
			$delimiter = strpos($username, ":");
			$length  = strlen($username) - $delimiter;
			$company = substr($username,  $length);
			return $this->invoke("Company.GetEndpoint", array("company"=>$company));
		}
		
		/** 
		* Returns the wsse authentication header string
		* @return string
		*/
		function generateAuthHeader ($username, $password)
		{
			$header = "";
			$nonce = md5(rand());
			$created = date("Y-m-d H:i:s");
			$passwordDigest = base64_encode(sha1($nonce . $created . $password));
			
			$header .= 'UsernameToken Username="'.$username.'", ';
			$header .= 'PasswordDigest="'.$passwordDigest.'", ';
			$header .= 'Nonce="'.$nonce.'", ';
			$header .= 'Created="'.$created.'" ';
			
			return $header;
			
		}
		
		/**
		* Invokes the API
		* 
		* Calls the API with the specified method.
		* @param string $method The fully qualified API method
		* @param obj $params OPTIONAL The object that contains the parameters. Generally specified as array("name"=>"value")
		* 
		*/
		function invoke($method, $params )
		{
			
			if($this->companyEndpoint)
				$url = $this->companyEndpoint."?method=".$method;
			else //default is used befor Company.GetEndpoint is called
				$url = $this->defaultEndpoint."?method=".$method;
				
			$ch = curl_init($url);
			if($this->username && $this->password)
			{
				$wsseHeader = "X-WSSE : ".$this->generateAuthHeader($this->username, $this->password);
				//set the security header
				curl_setopt($ch, CURLOPT_HTTPHEADER, array($wsseHeader));
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				
				$apiResponse = curl_exec($ch);
				curl_close($ch);
				return $apiResponse;
			
			}
			else //return false if no username or password
			{
				return false;
			}
		}
	}
	
?>