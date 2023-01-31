<?php
try {
    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();

    if (!isset($body['data']['order_id']) || !$body['data']['order_id']) throw new Exception("Informe o codigo do pedido para prosseguir", 400);

    $order->status = 'C';
    $order->id = $body['data']['order_id'];

    $validateOrderId = $order->verifyExistKitchenOrder();
    
    if ($validateOrderId == null) throw new Exception("Não existe pedido para cozinha com esse codigo de pedido", 400);
    
    $updateStatus = $order->updateStatus();

    $return = array(
        'status' => 'success',
        'data' => array(
            "order_item_id" => $updateStatus
        ),
        'message' => 'O pedido ' . $updateStatus . ' foi concluido e disponibilizado para entrega'
    );
    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
