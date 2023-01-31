<?php
try {
    if (!isset($body['data']['product_id']) || !$body['data']['product_id']) throw new Exception("Informe o produto para prosseguir", 400);

    if (!isset($body['data']['quantity']) || !$body['data']['quantity']) throw new Exception("Informe a quantidade do produto para prosseguir", 400);

    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();

    $payment_method = "choose";
    $change = 0.00;
    $status = "P";
    $total = 0.00;

    $user_id = $user->getTokenOwner($body['auth']['token']);

    

    if ($order->verifyExistPendingOrder($user_id['user_id']) != true) {
        $order->payment_method = $payment_method;
        $order->change = $change;
        $order->status = $status;
        $order->total = $total;
        $order->user_id = $user_id['user_id'];
        
        $order_id = $order->create();

        $orderItem->order_id = $order_id;
    } else {
        $order_id = $order->getOrderIdByUserId($user_id['user_id']);

        $orderItem->order_id = $order_id['id'];
    }

    if ($order_id == null) throw new Exception("Erro ao inserir o pedido no sistema!", 422);

    if ($product->verifyExistProductById($body['data']['product_id']) == false) throw new Exception("Informe um produto que existe em nosso sistema para prosseguir", 400);

    
    $orderItem->product_id = $body['data']['product_id'];
    $orderItem->quantity = $body['data']['quantity'];
    $data = $orderItem->getOrderItemIdAndQuantity();
    
    if (isset($data) && !empty($data) && isset($data[0]) && $data[0]['quantity'] > 0) {
        $order_item_id = $orderItem->update((intval($data[0]['quantity']) + $body['data']['quantity']), $order_id['id']);

        if ($order_item_id == null) throw new Exception("Erro ao atualizar produto no carrinho!", 422);

        $return = array(
            'status' => 'success',
            'data' => array(
                "order_item_id" => $order_item_id
            ),
            'message' => 'Foram acrescentado mais ' . $body['data']['quantity'] . ' unidades ao seu produto'
        );
        http_response_code(200);
        echo json_encode($return);
    } else {
        $order_item_id = $orderItem->create();

        if ($order_item_id == null) throw new Exception("Erro ao salvar produto no carrinho!", 422);

        $return = array(
            'status' => 'success',
            'data' => array(
                "order_item_id" => $order_item_id
            ),
            'message' => 'Produto adicionado com sucesso!'
        );
        http_response_code(200);
        echo json_encode($return);
    }
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
