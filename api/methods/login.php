<?php
try {
    if (!isset($body['data']['username']) || empty($body['data']['username'])) throw new Exception("Informe o usuário!", 401);

    if (!isset($body['data']['password']) || empty($body['data']['password'])) throw new Exception("Informe a senha!", 401);

    $user = new Login($body['data']['username']);

    if (empty($user->username) && empty($user->auth)) throw new Exception("Usuário inválido!", 401);
    $authentication = false;
    $authentication = md5($body['data']['password']) === $user->password ? true : false;

    if ($authentication === false) throw new Exception("Login ou senha inválidos!", 401);

    $login = new Login();
    $login->id = $user->id;
    $login->user_logout();
    $login->generate_token();
    $loadInformations = $login->loadInformations($user->id);

    if (!isset($login->token) || empty($login->token)) throw new Exception("Erro ao gerar o token!", 401);

    $return = array(
        'status' => 'success',
        'data' => array(
            'token' => $login->token,
            'name' => $user->name,
            'username' => $user->username,
            'user_level_id' => $loadInformations['user_level_id'],
            'user_level' => $loadInformations['user_level'],
            'user_id' => $loadInformations['user_id'],
        ),
        'message' => 'Login realizado com sucesso!'
    );
    http_response_code(200);
    echo json_encode($return);
} catch (Exception $e) {
    $return = array('status' => 'error', 'message' => "{$e->getMessage()}");
    http_response_code(is_numeric($e->getCode()) ?$e->getCode() : 500);
    echo json_encode($return);
}
