<?php
try {
    if (!isset($body['data']['order_id']) || !$body['data']['order_id']) throw new Exception("Informe o codigo do pedido para prosseguir", 400);

    if (!isset($body['data']['quantity']) || !$body['data']['quantity']) throw new Exception("Informe a quantidade do produto para prosseguir", 400);

    if (!isset($body['data']['product_id']) || !$body['data']['product_id']) throw new Exception("Informe o codigo produto que deve ser removido para prosseguir", 400);

    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();

    $user_id = $user->getTokenOwner($body['auth']['token']);
    $order_id = $order->getOrderIdByUserId($user_id['user_id']);

    $orderItem->quantity = $body['data']['quantity'];
    $orderItem->product_id = $body['data']['product_id'];
    $orderItem->order_id = $body['data']['order_id'];

    $data = $orderItem->getQuantityProductInOrder($body['data']['order_id']);

    $verify_product_order = $orderItem->getOrderItemIdAndQuantity();
    
    if ($verify_product_order == null) throw new Exception("Este codigo de produto não está contido nesse pedido", 400);

    if ($data == null) throw new Exception("Só é possivel remover um produto que ja tenha sido colocado no pedido ou em um pedido que esteja pendente", 400);

    if ($product->verifyExistProductById($body['data']['product_id']) == false) throw new Exception("Informe um produto que existe em nosso sistema para prosseguir", 400);

    if (isset($data) && $data['quantity'] > $body['data']['quantity']) {
        $order_item_id = $orderItem->update((intval($data['quantity']) - $body['data']['quantity']), $body['data']['order_id']);
        
        if ($order_item_id == null) throw new Exception("Erro ao atualizar produto no carrinho!", 422);

        $return = array(
            'status' => 'success',
            'data' => array(
                "order_item_id" => $order_item_id
            ),
            'message' => 'Foram removidos ' . $body['data']['quantity'] . ' unidades do seu produto'
        );
        http_response_code(200);
        echo json_encode($return);
    } else {
        $order_item_id = $orderItem->delete($body['data']['order_id']);

        if ($order_item_id == null) throw new Exception("Erro ao remover produto no carrinho!", 422);

        $return = array(
            'status' => 'success',
            'data' => array(
                "order_item_id" => $order_item_id
            ),
            'message' => 'Produto(s) removido com sucesso!'
        );
        http_response_code(200);
        echo json_encode($return);
    }
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
