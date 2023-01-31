<?php
try {
    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();

    $user_id = $user->getTokenOwner($body['auth']['token']);
    $order_id = $order->getOrderIdByUserId($user_id['user_id']);

    if (isset($body['data']['order_id'])) {
    } else {
        $resume = $order->getResumeOrder();

        if ($resume == null) throw new Exception("Não existe nenhum pedido pendente", 400);
        
        foreach ($resume as $item) {
            $orderId = $item['order_id'];
            if (isset($newArray[$orderId])) {
                $newArray[$orderId]['total'] += $item['total'];
                $newArray[$orderId]['names'][] = $item['quantity'] . 'x ' . $item['name'];
            } else {
                $newArray[$orderId] = array(
                    'customer_name' => $item['customer_name'],
                    'names' => array($item['quantity'] . 'x ' . $item['name']),
                    'customer_notes' => $item['customer_notes'],
                    'payment_method' => $item['payment_method'],
                    'total' => $item['total'],
                    'order_id' => $item['order_id']
                );
            }
        }

        $newArray = array_values($newArray);

        $return = array(
            'status' => 'success',
            'data' => $newArray
        );
    }


    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
