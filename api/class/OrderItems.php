<?php

class OrderItems
{
    public $id;
    public $product_id;
    public $quantity;
    public $order_id;
    public $order_item_id;


    public function __construct($id = false)
    {
        if ($id) {
            $this->id = $id;
        }
    }

    public function create()
    {
        $query = " INSERT INTO public.order_items (order_id, product_id, quantity)
                        VALUES (:order_id, :product_id, :quantity);";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':product_id', $this->product_id);
        $val->bindValue(':quantity', $this->quantity);
        $val->bindValue(':order_id', $this->order_id);

        $val->execute();
        return $this->order_id;
    }

    public function update($total_quantity, $order_id)
    {
        $query = "UPDATE public.order_items 
                        SET quantity = :total_quantity
                        WHERE order_id = :order_id AND product_id = :product_id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', $order_id);
        $val->bindValue(':total_quantity', $total_quantity);
        $val->bindValue(':product_id', $this->product_id);
        $val->execute();

        return $order_id;
    }

    public function delete($order_item_id)
    {
        $query = "DELETE FROM public.order_items WHERE order_id = :order_id AND product_id = :product_id";

        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', intval($order_item_id));
        $val->bindValue(':product_id', $this->product_id);
        $val->execute();
     
        return $order_item_id;
    }

    public function getValueTotal()
    {
        $query = " SELECT 
                    SUM(oi.quantity * p.value) AS total_value
                FROM
                    order_items oi
                JOIN
                    product AS p ON (p.id = oi.product_id)
                WHERE
                    oi.order_id = :order_id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', $this->order_id);
        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    public function getOrderItemResume()
    {
        $query = " SELECT 
                    p.name, 
                    oi.quantity, 
                    p.value, 
                    SUM(oi.quantity * p.value) AS final_value_product,
                    SUM(SUM(oi.quantity * p.value)) OVER() AS total_value_all_products
                FROM
                    order_items oi
                JOIN
                    product AS p ON (p.id = oi.product_id)
                JOIN
                    public.orders AS o ON (o.id = oi.order_id)
                WHERE
                    oi.order_id = :order_id 
                GROUP BY 
                    p.name, 
                    oi.quantity, 
                    p.value";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', $this->order_id);
        $val->execute();

        $rows = $val->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function getOrderItemIdAndQuantity()
    {
        $query = " SELECT 
                    quantity, id
                FROM
                    public.order_items
                WHERE
                    order_id = :order_id AND product_id = :product_id
                LIMIT 1";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', $this->order_id);
        $val->bindValue(':product_id', $this->product_id);
        $val->execute();
        $rows = $val->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function getQuantityProductInOrder()
    {
        $query = " SELECT 
                    quantity
            FROM
                public.order_items
            WHERE
                order_id = :order_id AND
                product_id = :product_id";
        $conn = Db::Connect();
        $val = $conn->prepare($query);
        $val->bindValue(':order_id', $this->order_id);
        $val->bindValue(':product_id', $this->product_id);
        $val->execute();
        $row = $val->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
}
