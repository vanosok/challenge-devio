<?php
class Login
{
    public $id;
    public $name;
    public $username;
    public $password;
    public $email;
    public $users_groups_id;
    public $group;
    public $auth;
    public $profile_id;
    public $profile;
    public $token;

    public function __construct($username = false)
    {
        if ($username) {
            $this->username = $username;
            $this->verify_login();
            // limpa os tokens expirados
            $this->clear_token();
        }
    }

    public function verify_login()
    {
        $query = "SELECT 
                    u.id, u.name, u.username, u.password
                FROM 
                    \"user\" as u 
                WHERE 
                    u.username = :username AND 
                    u.status = 'A'
                LIMIT 1";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':username', $this->username);
        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);
        $this->id = isset($row['id']) ? $row['id'] : null;
        $this->name = isset($row['name']) ? $row['name'] : null;
        $this->username = isset($row['username']) ? $row['username'] : null;
        $this->password = isset($row['password']) ? $row['password'] : null;
    }

    public function user_logout()
    {
        $query = "DELETE FROM token WHERE user_id = :user_id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue('user_id', $this->id);
        $val->execute();
    }

    public static function token_logout($token = null)
    {
        $query = "DELETE FROM token WHERE token = :token";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue('token', $token);
        $val->execute();
    }

    public static function verify_token($token = null)
    {
        $query = "SELECT 
                    user_id
                FROM
                    token
                WHERE
                    token = :token and CURRENT_TIMESTAMP <= expires_in
                LIMIT 1";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':token', $token);
        $val->execute();
        $data = $val->fetchColumn(0);

        if ($data) {
            $query = "UPDATE
                        token
                    set
                        expires_in = CURRENT_TIMESTAMP + interval '" . LOGIN_TIME_EXPIRE_TOKEN . "' 
                    WHERE
                        token = :token";
            $conn = Db::Connect();
            $val = $conn->prepare($query);
            $val->bindValue(':token', $token);
            $val->execute();
        }

        return $data;
    }

    public function generate_token()
    {
        $this->token = md5($this->id . time() . RandString(12));

        $query = "INSERT INTO token (token, user_id, expires_in)
                  VALUES (:token, :user_id, CURRENT_TIMESTAMP + interval '" . LOGIN_TIME_EXPIRE_TOKEN . "')";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':token', $this->token);
        $val->bindValue(':user_id', $this->id);
        $val->execute();

        return $this->token;
    }

    public function clear_token()
    {
        $query = "delete from token where expires_in <= CURRENT_TIMESTAMP;";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->execute();
    }

    public static function loadInformations($id)
    {
        $query = "SELECT 
                    ul.id AS user_level_id,
                    ul.description AS user_level,
                    u.id AS \"user_id\"
                FROM 
                    user_level ul
                LEFT JOIN
                    public.user u ON (u.user_level_id = ul.id)
                WHERE
                    u.id = :id
        ";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':id', $id);
        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
}
