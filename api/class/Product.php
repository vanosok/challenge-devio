<?php

class Product
{
    public $id;
    public $people_id;
    public $segment_id;
    public $product_type_id;
    public $name;
    public $description;
    public $role;
    public $value;
    public $value_promotion;
    public $expiration_data;
    public $inserted_date;
    public $status;
    public $minimum_quantity;
    public $image;
    public $file_path;
    public $upload_file_status;


    public function __construct($id = false)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public static function list()
    {
        $query = "SELECT name, description, value, id FROM product;";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->execute();
        $rows = $val->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public static function findProductByID($product_id)
    {
        $query = "SELECT name, description, value
        FROM product          
        WHERE id = :id;";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':id', $product_id);
        $val->execute();
        $row = $val->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public static function findProductByName($product_name)
    {
        $query = "SELECT name, description, value
        FROM product
        WHERE name LIKE '%' || :product_name || '%';";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':product_name', $product_name);
        $val->execute();
        $row = $val->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    public function verifyExistProductById($product_id)
    {
        $query = "SELECT 
                    id
                FROM 
                    public.product
                WHERE 
                    id = :product_id
                LIMIT 1";

        $conn = Db::Connect();
        $verifyExistProductById = $conn->prepare($query);
        $verifyExistProductById->bindValue(':product_id', $product_id);
        $verifyExistProductById->execute();
        $row = $verifyExistProductById->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return true;
        } else {
            return false;
        }
    }
}
