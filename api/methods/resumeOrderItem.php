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

    $newArray = array();
    $total = 0;

    foreach ($resume as $item) {
        $newArray[] = array(
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'value' => $item['value'],
            'final_value_product' => $item['final_value_product']
        );
        $total += $item['final_value_product'];
    }

    $newArray[] = array(
        'total_value_all_products' => $total
    );

    $newArray = array_values($newArray);

    $return = array(
        'status' => 'success',
        'data' => $newArray
    );

    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
