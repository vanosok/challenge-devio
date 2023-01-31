<?php
try {
    $productList = Product::list();

    $return = array(
        'status' => 'success',
        'data' => $productList
    );

    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
