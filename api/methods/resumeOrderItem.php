<?php
try {
    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();

    $user_id = $user->getTokenOwner($body['auth']['token']);
    $order_id = $order->getOrderIdByUserId($user_id['user_id']);
  
    if ($order_id == false) throw new Exception("Nenhum pedido encontrado para esse usuário", 400);
    
    $orderItem->order_id = $order_id['id'];
    
    $resume = $orderItem->getOrderItemResume();

    $return = array(
        'status' => 'success',
        'data' => $resume
    );
   
    http_response_code(200);
    echo json_encode($return);

} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
