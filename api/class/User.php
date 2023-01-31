<?php
class User
{
    public $id;
    public $username;
    public $name;
    public $password;
    public $photo;
    public $inserted_date;
    public $status;
    public $user_level_id;

    public function create()
    {
        $query = " INSERT INTO public.user (username, name, password, inserted_date, status, user_level_id)
                    VALUES (:username, :name, :password, now(), 'A', :user_level_id);";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':name', $this->name);
        $val->bindValue(':username', $this->username);
        $val->bindValue(':password', $this->password);
        $val->bindValue(':user_level_id', $this->user_level_id);
        $val->execute();

        return $conn->lastInsertId();
    }

    public function verifyExsitUserName($username)
    {
        $query = "SELECT 
                    id
                FROM 
                    public.user
                WHERE 
                    username = :username
                LIMIT 1";

        $conn = Db::Connect();
        $verifyExistCpf = $conn->prepare($query);
        $verifyExistCpf->bindValue(':username', $username);
        $verifyExistCpf->execute();
        $row = $verifyExistCpf->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        $query = "DELETE FROM public.user
                  WHERE id = :id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':id', $id);

        if ($val->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function user_list($user_id)
    {
        $query = "SELECT u.name, u.username, np.cpf, to_char(np.birth_date, 'DD-MM-YYYY') AS birth_date, CASE WHEN c.gender = 'M' THEN 'Masculino' WHEN c.gender = 'F' THEN 'Feminino' ELSE 'Não desejo informar' END AS gender
        FROM public.user u  
            LEFT JOIN client_user cu ON (cu.user_id = u.id)
        
            LEFT JOIN client c ON (c.people_id = cu.people_id)
        
            LEFT JOIN natural_person np ON (np.people_id = c.people_id)
        
        WHERE u.id = :id;";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':id', $user_id);
        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    public static function getTokenOwner($token)
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
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public static function getNameUser($user_id)
    {
        $query = "SELECT 
                        name
                    FROM
                        public.user
                    WHERE
                        id = :id
                    LIMIT 1";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':id', $user_id);

        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
}
