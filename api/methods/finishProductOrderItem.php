<?php
try {
    if (!isset($body['data']['payment_method']) || !$body['data']['payment_method']) throw new Exception("Informe o metodo de pagamento para prosseguir", 400);

    $orderItem = new OrderItems();
    $order = new Order();
    $user = new User();
    $product = new Product();
    $utils = new Utils();

    $user_id = $user->getTokenOwner($body['auth']['token']);
    $order_id = $order->getOrderIdByUserId($user_id['user_id']);

    if ($order->verifyExistPendingOrder($user_id['user_id']) == false) throw new Exception("Você não possui pedido pendente para finalizar favor fazer outro pedido ou verificar se o pedido ja não está para cozinha.", 400);

    $orderItem->order_id = $order_id['id'];

    $total_payble =  $orderItem->getValueTotal();

    if ($body['data']['payment_method'] == "M") {
        $order->payment_method = "Money";
        if (!isset($body['data']['money_value']) || !$body['data']['money_value']) throw new Exception("Informe quanto você irá pagar para que possa saber o troco", 400);

        if ($total_payble['total_value'] < intval($body['data']['money_value'])) {
            $change = ($body['data']['money_value'] - $total_payble['total_value']);
            $order->change = $change;
        } else if ($total_payble['total_value'] == $body['data']['money_value']) {
            $order->change = 0.00;
        } else if ($total_payble['total_value'] > $body['data']['money_value']) {
            throw new Exception("A quantidade informada para o pagamento e menor do que o tanto a pagar favor insira um valor maior ou igual ao que deve pagar para prosseguir", 400);
        }
    } else if ($body['data']['payment_method'] == "CC") {
        $order->payment_method = "CreditCard";
        $order->change = 0.00;
    } else if ($body['data']['payment_method'] == "DC") {
        $order->payment_method = "DebitCard";
        $order->change = 0.00;
    } else {
        throw new Exception("Informe o método de pagamento escolha entre um desses (M = Money, CC = CreditCard, DC = DebitCard", 400);
    }

    if (isset($body['data']['customer_name'])) {
        $order->customer_name = $body['data']['customer_name'];
    } else {
        $user_name = $user->getNameUser($user_id['user_id']);
        $order->customer_name = $user_name['name'];
    }

    if (isset($body['data']['customer_notes'])) {
        $order->customer_notes = $body['data']['customer_notes'];
    } else {
        $user_name = $user->getNameUser($user_id['user_id']);
        $order->customer_notes = "No notes";
    }

    $order->total = $total_payble['total_value'];
    $order->status = "K";
    $order->id = $order_id['id'];
    

    $update = $order->update();
    if ($update != null) {
        $itens = [];

        $item = $orderItem->getOrderItemResume();


        foreach ($item as $item_data) {
            $itens[] = array(
                'nome' => $item_data['name'],
                'valor' => $item_data['value'],
                'quantidade' => $item_data['quantity']
            );
        }

        $order_data = array(
            'numero' => $order_id['id'],
            'cliente' => isset($body['data']['customer_name']) ? $body['data']['customer_name'] : $user_name['name'],
            'itens' => $itens,
            'total' => $total_payble
        );

        
        $pdf = $utils->createPDF($order_data, false);

        //Simulação de print utilizando a bibilioteca https://github.com/mike42/escpos-php
        //$utils->thermalPrinter("123.132.42:30", $pdf);
    }

    if ($update != null) {

        $return = array(
            'status' => 'success',
            'data' => array(
                "order_id" => $update
            ),
            'message' => 'Seu pedido foi finalizado com sucesso agora é só aguardar a entrega!.'
        );
        http_response_code(200);
        echo json_encode($return);
    }
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
