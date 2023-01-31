<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once './functions/global.php';

try {
    cors();
    if (trim($_SERVER['REQUEST_METHOD']) != 'POST') throw new Exception("Requisição inválida!", 401);

    $headers = apache_request_headers();

    if (!isset($headers['Content-Type']) || (isset($headers['Content-Type']) && strtolower($headers['Content-Type']) != 'application/json')) throw new Exception("É preciso informar um Content-Type válido!", 401);

    if (!isset($_SERVER['REQUEST_URI'])) throw new Exception("Método inválido!", 401);

    $method = ValidateUrl($_SERVER['REQUEST_URI'], 1);

    $dataString = file_get_contents("php://input");
    parse_str($dataString, $myString);
    extract($myString);
    $body = json_decode($dataString, true);
    $freeMethods = array('login', 'createUser', 'listProduct', 'findProduct');

    if (isset($body['auth']['token']) && !empty($body['auth']['token']) && !in_array($method, $freeMethods)) {
        $verify_token = Login::verify_token($body['auth']['token']);

        if (!$verify_token) throw new Exception("Autenticação inválida! Realize o login novamente.", 401);
        $user_id_token = $verify_token;

        switch ($method) {

                //Token
            case 'logout':
                require './methods/' . $method . '.php';
                break;

                //OrderItem
            case 'addProductOrderItem':
                require './methods/' . $method . '.php';
                break;
            case 'removeProductOrderItem':
                require './methods/' . $method . '.php';
                break; 
            case 'resumeOrderItem':
                require './methods/' . $method . '.php';
                break; 
            case 'finishProductOrderItem':
                require './methods/' . $method . '.php';
                break;  
                
                //Order
            case 'resumeOrderKitchen':
                require './methods/' . $method . '.php';
                break;
            case 'finishOrderKitchen':
                require './methods/' . $method . '.php';
                break;
                
            default:
                require './methods/invalid.php';
                break;
        }
    } else {
        if ($method == 'login') {
            if (ValidateUrl($method) != 'login') throw new Exception("Para acessar o método, você precisa estar autenticado!", 401);
        } else if ($method == 'createUser') {
            if (ValidateUrl($method) != 'createUser') throw new Exception("Para acessar o método, você precisa estar autenticado!", 401);
        } else if ($method == 'listProduct') {
            if (ValidateUrl($method) != 'listProduct') throw new Exception("Para acessar o método, você precisa estar autenticado!", 401);
        } else if ($method == 'findProduct') {
            if (ValidateUrl($method) != 'findProduct') throw new Exception("Para acessar o método, você precisa estar autenticado!", 401);
        } else {
            throw new Exception("Para acessar o método, você precisa estar autenticado!", 401);
        }

        require './methods/' . $method . '.php';
    }
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}

function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
