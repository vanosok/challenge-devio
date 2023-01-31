<?php
try {
    if (!isset($body['data']['name']) || !$body['data']['name']) throw new Exception("Informe o nome do usuário!", 400);
    
    if (strlen($body['data']['name']) > 150) throw new Exception("O nome inserido está muito grande!", 400);

    if (!isset($body['data']['email']) || !$body['data']['email']) throw new Exception("Informe o email do usuário!", 400);

    if (!isset($body['data']['password']) || !$body['data']['password']) throw new Exception("Informe o senha do usuário!", 400);
    
    if (!isset($body['data']['confirm_password']) || !$body['data']['confirm_password']) throw new Exception("Informe a confirmação de senha!", 400);

    if ($body['data']['confirm_password'] != $body['data']['password'] || $body['data']['password'] != $body['data']['confirm_password']) throw new Exception("Confirmação da senha é diferente da senha inserida!", 400);

    $utils = new Utils();
    $validationTextType = $utils->validationTextType($body['data']['email'], 'email');
    //Validação do email fornecido no Client
    if ($validationTextType == false) throw new Exception("O email: " . ($body['data']['email']) . " não é valido!", 400);

    $user = new User();
    $verifyExistUserName = $user->verifyExsitUserName($body['data']['email']);
    //Validação do usuário fornecido na User
    if ($verifyExistUserName == true) throw new Exception("O usuário: " . $body['data']['email'] . " já existe em nosso sistema!", 400);

    $user = new User();
    $user->name = $body['data']['name'];
    $user->username = $body['data']['email'];
    $user->password = $body['data']['password'] ? md5($body['data']['password']) : null;
    $user->photo = isset($body['data']['photo']) ? $body['data']['photo'] : NULL;
    $user->user_level_id = 50;
    $user_id = $user->create();

    $return = array(
        'status' => 'success',
        'data' => array(
            "user_id" => $user_id
        ),
        'message' => 'Usuário criado com sucesso!'
    );
    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ? $e->getCode() : 500);
    echo json_encode($return);
}
