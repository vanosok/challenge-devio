<?php

class Order
{
    public $id;
    public $user_id;
    public $payment_method;
    public $total;
    public $change;
    public $status;
    public $customer_name;
    public $customer_notes;

    public function __construct($id = false)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public function create()
    {
        $query = " INSERT INTO public.orders (user_id, payment_method, total, change, status)
                    VALUES (:user_id, :payment_method, :total, :change, :status);";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':user_id', $this->user_id);
        $val->bindValue(':payment_method', $this->payment_method);
        $val->bindValue(':total', $this->total);
        $val->bindValue(':change', $this->change);
        $val->bindValue(':status', $this->status);

        $val->execute();
        return $conn->lastInsertId();
    }

    public function update()
    {
        $query = "UPDATE public.orders
                        SET payment_method = :payment_method, total = :total, change = :change, status = :status,  customer_notes = :custumer_notes, customer_name = :custumer_name
                    WHERE id = :id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);

        $val->bindValue(':payment_method', $this->payment_method);
        $val->bindValue(':total', $this->total);
        $val->bindValue(':change', $this->change);
        $val->bindValue(':status', $this->status);
        $val->bindValue(':custumer_notes', $this->customer_notes);
        $val->bindValue(':custumer_name', $this->customer_name);
        $val->bindValue(':id', $this->id);

        $val->execute();

        return $this->id;
    }

    public function verifyExistPendingOrder($user_id)
    {
        $query = "SELECT 
                    id
                FROM 
                    public.orders
                WHERE 
                    user_id = :user_id AND
                    status = 'P'
                LIMIT 1";

        $conn = Db::Connect();
        $verifyExistPendingOrder = $conn->prepare($query);
        $verifyExistPendingOrder->bindValue(':user_id', $user_id);
        $verifyExistPendingOrder->execute();
        $row = $verifyExistPendingOrder->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function verifyExistKitchenOrder()
    {
        $query = "SELECT 
                    id
                FROM 
                    public.orders
                WHERE 
                    id = :id AND
                    status = 'K'
                LIMIT 1";

        $conn = Db::Connect();
        $verifyExistPendingOrder = $conn->prepare($query);
        $verifyExistPendingOrder->bindValue(':id', $this->id);
        $verifyExistPendingOrder->execute();
        $row = $verifyExistPendingOrder->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    public function updateStatus()
    {
        $query = "UPDATE public.orders
                        SET status = :status
                    WHERE id = :id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        
        $val->bindValue(':status', $this->status);
        $val->bindValue(':id', $this->id);

        $val->execute();

        return $this->id;
    }

    public function getOrderIdByUserId($user_id)
    {
        $query = "SELECT 
                    o.id
                FROM  
                    public.orders AS o
                JOIN
	                public.user AS u ON (o.user_id = u.id)
                WHERE 
                    o.user_id = :user_id AND
                    o.status = 'P'
                LIMIT 1";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':user_id', $user_id);

        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getResumeOrder()
    {
        $query = "   SELECT 
                        o.customer_name, o.customer_notes,o.payment_method ,  p.name, oi.quantity, o.total , o.change, oi.order_id
                    FROM 
                        orders o
                    JOIN 
                        order_items AS oi ON (oi.order_id = o.id)
                    JOIN
                        product AS p ON (p.id = oi.product_id)
                    WHERE 
                        status = 'K'";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->execute();
        
        $row = $val->fetchAll(PDO::FETCH_ASSOC);

        return $row;
    }
}
