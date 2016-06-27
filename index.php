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

/**
 * Deliver HTTP Response
 * @param string $format The desired HTTP response content type: [json, html, xml]
 * @param string $api_response The desired HTTP response data
 * @return void
 **/
 
include("mysqldb/mySqlDbInit.php");
include("mysqldb/mySqlDbCrudOperations.php");

//MySqlDbCrudOperations::insertOrUpdateHttpPairs("/test", "POST", "200", "A-OK bro!");
//MySqlDbCrudOperations::insertOrUpdateHttpPairs("/test2", "POST", "200", "Hello dave...");
//MySqlDbCrudOperations::deleteFromHttpPairs("/test2", "POST");
//MySqlDbCrudOperations::updateHttpPairs("/test", "GET", 201, "Super!!!");

//MySqlDbCrudOperations::printResultSet(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs());

//MySqlDbCrudOperations::printResultSet(MySqlDbCrudOperations::getResultSetForSelectOneFromHttpPairs("/test2", "POST"));
 
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

		// Format data into a JSON response
		$json_response = json_encode($api_response);

		// Deliver formatted data
		echo $json_response;

	}elseif( strcasecmp($format,'xml') == 0 ){

		// Set HTTP Response Content Type
		header('Content-Type: application/xml; charset=utf-8');

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

$requestType = $_SERVER['REQUEST_METHOD'];
$requestUrl = $_SERVER['REQUEST_URI'];


// Set default HTTP response of 'ok'
$response['code'] = 0;
$response['status'] = 404;
$response['data'] = NULL;

// --- Step 2: Process Request

// Method A: Say Hello to the API

$method = $_GET['method'];

if( strcasecmp($method,'httppair') == 0)
{
	  switch($requestType) {
	  case 'PUT': //insert or update
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['data'] = "Hello World " .  $requestType . " " . $requestUrl . " " . $_GET['method'];
		  break;
	 
	  case 'DELETE': //delete
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['data'] = "Hello World " .  $requestType . " " . $requestUrl . " " . $_GET['method'];
		  break;
	 
	  case 'GET': //select
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['data'] = MySqlDbCrudOperations::getResultSetString(MySqlDbCrudOperations::getResultSetForSelectAllFromHttpPairs()) .  $requestType . " " . $requestUrl . " " . $_GET['method'];
			//$response['data'] =  $requestType . " " . $requestUrl . " " . $_GET['method'];
		  break;
		  
	  case 'POST': //update
			$response['code'] = 1;
			$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
			$response['data'] = "Hello World " .  $requestType . " " . $requestUrl . " " . $_GET['method'];
		  break;
		  
	  default:
		  header('HTTP/1.1 405 Method Not Allowed');
		  header('Allow: GET, PUT, DELETE');
		  break;
	  }
}

if( strcasecmp($method,'hello') == 0){
	$response['code'] = 1;
	$response['status'] = $api_response_code[ $response['code'] ]['HTTP Response'];
	$response['data'] = "Hello World " .  $requestType . " " . $requestUrl . " " . $_GET['method'];
}

// --- Step 3: Deliver Response

// Return Response to browser
deliver_response($_GET['format'], $response);




?>
    