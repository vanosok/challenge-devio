<?php
$return = array('status' => 'error', 'message' => 'Método inválido!');
http_response_code(401);
echo json_encode($return);
