<?php
try {
    if (isset($body['data']['product_id']) && is_numeric($body['data']['product_id'])) {
        $list = Product::findProductByID($body['data']['product_id']);
    } else if (isset($body['data']['product_name'])) {
        $list = Product::findProductByName($body['data']['product_name']);
    } else {
        throw new Exception("Nenhum produto foi encontrado!", 400);
    }

    if ($list == null) throw new Exception("Nenhum produto foi encontrado!", 400);
    $return = array(
        'status' => 'success',
        'data' => $list
    );
   
    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
