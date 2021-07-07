<?php
include_once 'Model.php';

class Cart extends Model {
    public string $table = 'carts';
    public string $primary_key = 'id';
    public string $columns = 'id, name, phone, email, address, is_ordered';

    public function add_cart(string $name, string $phone, string $email, string $address, array|string $glasses_id, array|string $quantities) {
        //Add a new cart
        $date = date('Y-m-d H:i:s');
        $cart_id = $this->db->insert('carts', 'name, phone, email, address, time, is_ordered', "$name, $phone, $email, $address, $date, 0");
        
        //Add glasses to cart
        for ($index = 0; $index < count($glasses_id); $index++) {
            $glass_id = $glasses_id[$index];
            $quantity = $quantities[$index];
            $glass = $this->db->query("SELECT price, discount, quantity FROM glasses WHERE id = '$glass_id'")->fetch_assoc();
            $price = $glass['price'] - ($glass['price'] * $glass['discount'] / 100);
            $new_quantity = $glass['quantity'] - $quantity;
            
            $this->db->insert('carts_glasses', 'glass_id, cart_id, quantity, price', "$glass_id, $cart_id, $quantity, $price");
            //$this->db->update('glasses', 'quantity', "$new_quantity", "id = $glass_id");
        }

        return $cart_id;
    }

    public function get_glasses($glass_id) : array {
        return $this->db->query("SELECT * FROM glasses WHERE id = $glass_id")->fetch_assoc();
    }

    public function get_orders() {
        return $this->db->query('SELECT * FROM carts ORDER BY time DESC');
    }

    public function get_order_cart($cart_id) : array {
        return $this->db->query("SELECT * FROM carts WHERE id = $cart_id")->fetch_assoc();
    }

    public function get_order_glasses($cart_id) {
        return $this->db->query("SELECT glasses.id AS id, name, trademark, carts_glasses.quantity AS quantity, carts_glasses.price AS price FROM glasses INNER JOIN carts_glasses ON glasses.id = carts_glasses.glass_id WHERE cart_id = $cart_id");
    }

    public function verify_order($id) {
        $carts_glasses = $this->db->query("SELECT * FROM carts_glasses WHERE cart_id = $id");
        while($cart_glass = $carts_glasses->fetch_assoc()) {
            $glass_id = $cart_glass['glass_id'];
            $glass = $this->db->query("SELECT * FROM glasses WHERE id = $glass_id")->fetch_assoc();
            $new_quantity = $glass['quantity'] - $cart_glass['quantity'];
            $this->db->update('glasses', 'quantity', "$new_quantity", "id = $glass_id");
        }
        $this->db->update('carts', 'is_ordered', '1', "id = $id");
    }
}
//Chưa trừ được bảng glasses