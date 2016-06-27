<?php

//print_r(array_keys($_POST));
//print_r(array_values($_POST));

$requestType = null;
switch($_POST['httpRequestTypes'])
{
		case 'GET' : $requestType = HttpRequest::METH_GET; break;
		case 'POST' : $requestType = HttpRequest::METH_POST; break;
		case 'PUT' : $requestType = HttpRequest::METH_PUT; break;
		case 'DELETE' : $requestType = HttpRequest::METH_DELETE; break;
		case 'OPTIONS' : $requestType = HttpRequest::METH_OPTIONS; break;
		case 'HEAD' : $requestType = HttpRequest::METH_HEAD; break;
		case 'PATCH' : $requestType = HttpRequest::METH_PATCH; break;
		case 'PROPFIND' : $requestType = HttpRequest::METH_PROPFIND; break;
		case 'COPY' : $requestType = HttpRequest::METH_COPY; break;
		case 'MOVE' : $requestType = HttpRequest::METH_MOVE; break;
		default:
		  header('HTTP/1.1 405 Method Not Allowed');
		  header('Allow: GET, PUT, DELETE, POST, OPTIONS, HEAD, PATCH, PROPFIND, COPY, MOVE');
		  break;
}


$request = new HttpRequest($_POST['urlTarget '], $requestType);
$request->setOptions(array('redirect' => 10));

try {
    $request->send();
    echo ($request->getResponseBody());
} catch (HttpException $ex) {
    echo $ex;
}

?>