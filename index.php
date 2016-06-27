<?php
/*
	API Demo

	This script provides a RESTful API interface for a web application

	Input:

		$_GET['format'] = [ json | html | xml ]
		$_GET['method'] = []

	Output: A formatted HTTP response

	Author: Mark Roland
	Edited : Kiren Padayachee

	History:
		11/13/2012 - Created

*/

// --- Step 1: Initialize variables and functions


 
include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

//MySqlDbCrudOperations::updateHttpPairs("/test2", "POST", 200, "Wassup Dave...");
//MySqlDbCrudOperations::insertOrUpdateHttpPairs("/newapi", "GET", 200, "nuuuuuuuu...");
 
 /**
 * Deliver HTTP Response
 * @param string $format The desired HTTP response content type: [json, html, xml]
 * @param string $api_response The desired HTTP response data
 * @return void
 **/
function deliver_response($format, $api_response){

	// Define HTTP responses
	$http_response_code = array(
		200 => 'OK',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found'
	);

	// Set HTTP Response
	header('HTTP/1.1 '.$api_response['status'].' '.$http_response_code[ $api_response['status'] ]);

	// Process different content types
	if( strcasecmp($format,'json') == 0 ){

		// Set HTTP Response Content Type
		header('Content-Type: application/json; charset=utf-8');
		
		if($api_response['isGet'] )
		{
			$api_response['data'] = MySqlDbCrudOperations::getResultSetAsArray($api_response['data']);
		}

		// Format data into a JSON response
		$json_response = json_encode($api_response);

		// Deliver formatted data
		echo $json_response;

	}elseif( strcasecmp($format,'xml') == 0 ){

		// Set HTTP Response Content Type
		header('Content-Type: application/xml; charset=utf-8');
		
		if($api_response['isGet'] )
		{
			$api_response['data'] = MySqlDbCrudOperations::getResultSetAsXmlString($api_response['data']);
		}

		// Format data into an XML response (This is only good at handling string data, not arrays)
		$xml_response = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
			'<response>'."\n".
			"\t".'<code>'.$api_response['code'].'</code>'."\n".
			"\t".'<data>'.$api_response['data'].'</data>'."\n".
			'</response>';

		// Deliver formatted data
		echo $xml_response;

	}else{

		// Set HTTP Response Content Type (This is only good at handling string data, not arrays)
		header('Content-Type: text/html; charset=utf-8');
		
		if($api_response['isGet'] )
		{
			$api_response['data'] = MySqlDbCrudOperations::getResultSetAsHtmlString($api_response['data']);
		}

		// Deliver formatted data
		echo $api_response['data'];
	}
	
	// End script process
	exit;

}


// Define API response codes and their related HTTP response
$api_response_code = array(
	0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),
	1 => array('HTTP Response' => 200, 'Message' => 'Success'),
	2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),
	3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),
	4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),
	5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),
	6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')
);




// Set default HTTP response of 'ok'
$response['code'] = 0;
$response['status'] = 404;
$response['data'] = NULL;
$response['isGet'] = false;

// --- Step 2: Process Request

$method = $_GET['method'];
$requestType = $_SERVER['REQUEST_METHOD'];
$requestUrl = $_SERVER['REQUEST_URI'];



if(strcasecmp($method,'httppair') != 0)
{
	$resultSet = MySqlDbCrudOperations::getResultSetForSelectOneFromHttpPairs($method, $requestType);
	
	if($resultSet->num_rows > 0)
	{
		$arr = MySqlDbCrudOperations::getResultSetAsArray($resultSet);
		$response['code'] = 1;
		$response['status'] = array_values($arr)[0]["httpResponseStatusCode"];
		$response['isGet'] = false;
		$response['data'] = array_values($arr)[0]["httpResponseMessage"];
	}
	else
	{
		$response['code'] = 1;
		$response['status'] = 405;
		$response['isGet'] = false;
		$response['data'] = "Error : API " . $method . " not found! You can add it if you want to.";
	}
}

if( strcasecmp($method,'httppair') == 0)
{
	  switch($requestType) {
	  case 'PUT': //insert or update
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['isGet'] = false;
			if(array_key_exists("httpRequestUrl",$_GET) &&  array_key_exists("httpRequestType",$_GET) && array_key_exists("httpResponseStatusCode",$_GET) && array_key_exists("httpResponseMessage",$_GET))
			{
				$response['data'] = MySqlDbCrudOperations::insertOrUpdateHttpPairs($_GET["httpRequestUrl"], $_GET["httpRequestType"], $_GET["httpResponseStatusCode"], $_GET["httpResponseMessage"]);
				if($response['data'] == 0)
				{
					$response['data'] = "Insert/Update success!";
				}
				else
				{
					$response['data'] = "Insert/Update failed!";
				}
			}
			else
			{
				$response['data'] = "Insert/Update failed. Check parameters : httpRequestUrl, httpRequestType, httpResponseStatusCode, httpResponseMessage";
			}
		  break;
	 
	  case 'DELETE': //delete
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['isGet'] = false;
			if(array_key_exists("httpRequestUrl",$_GET) &&  array_key_exists("httpRequestType",$_GET))
			{
				$response['data'] = MySqlDbCrudOperations::deleteFromHttpPairs($_GET["httpRequestUrl"], $_GET["httpRequestType"]);
				if($response['data'] == 0)
				{
					$response['data'] = "Delete success!";
				}
				else
				{
					$response['data'] = "Delete failed!";
				}
			}
			else
			{
				$response['data'] = "Delete failed. Check parameters : httpRequestUrl, httpRequestType";
			}
		  break;
	 
	  case 'GET': //select
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['isGet'] = true;
			
			if(array_key_exists("httpRequestUrl",$_GET) &&  array_key_exists("httpRequestType",$_GET))
			{
				$response['data'] = MySqlDbCrudOperations::getResultSetForSelectOneFromHttpPairs($_GET["httpRequestUrl"], $_GET["httpRequestType"]);
				if($response['data']->num_rows == 0)
				{
					$response['data'] = "No rows found for httpRequestUrl=" . $_GET["httpRequestUrl"] . " and httpRequestType=" . $_GET["httpRequestType"];
					$response['isGet'] = false;
				}
			}
			else
			{
				$response['data'] = MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs();
			}	
			
		  break;
		  
	  case 'POST': //update
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['isGet'] = false;
			if(array_key_exists("httpRequestUrl",$_GET) &&  array_key_exists("httpRequestType",$_GET) && array_key_exists("httpResponseStatusCode",$_GET) && array_key_exists("httpResponseMessage",$_GET))
			{
				$response['data'] = MySqlDbCrudOperations::updateHttpPairs($_GET["httpRequestUrl"], $_GET["httpRequestType"], $_GET["httpResponseStatusCode"], $_GET["httpResponseMessage"]);
				if($response['data'] == 0)
				{
					$response['data'] = "Update success!";
				}
				else
				{
					$response['data'] = "Update failed. No rows found for httpRequestUrl=" . $_GET["httpRequestUrl"] . " and httpRequestType=" . $_GET["httpRequestType"];
				}
			}
			else
			{
				$response['data'] = "Update failed. Check parameters : httpRequestUrl, httpRequestType, httpResponseStatusCode, httpResponseMessage";
			}
		  break;
		  
	  default:
		  header('HTTP/1.1 405 Method Not Allowed');
		  header('Allow: GET, PUT, DELETE, POST');
		  break;
	  }
}

// --- Step 3: Deliver Response

// Return Response to browser
deliver_response($_GET['format'], $response);

?>
    