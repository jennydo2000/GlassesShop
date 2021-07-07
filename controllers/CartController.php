<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Glass.php';
include_once __DIR__ . '/../models/Cart.php';

class CartController extends Controller {
    public function index($request) {
        $session = $request['SESSION'];
        $carts = isset($session['carts']) ? $session['carts'] : [];

        $model = new Glass();
        $data = [];
        foreach ($carts as $cart) {
            $id = $cart['id'];
            array_push($data, $model->index('id, name, trademark, price, discount', '', "id = $id")->fetch_assoc());
        }
        $this->view('cart', ['carts' =>$data]);
    }

    public function view_orders() {
        if (!$this->authorize())
            return false;
        
        $model = new Cart();
        $orders = $model->get_orders();

        $this->view('order', ['data' => $orders]);
    }

    public function view_order($request) {
        if (!$this->authorize())
            return false;

        $get = $request['GET'];

        $id = $get['id'];
        
        $model = new Cart();
        $cart = $model->get_order_cart($id);
        $glasses = $model->get_order_glasses($id);

        $this->view('order-detail', ['cart' => $cart, 'glasses' => $glasses]);
    }

    public function verify_order($request) {
        if (!$this->authorize())
            return false;

        $get = $request['GET'];

        $id = $get['id'];

        $model = new Cart();

        $model->verify_order($id);

        return true;
    }

    public function buy($request) {
        $carts = isset($_SESSION['carts']) ? $_SESSION['carts'] : [];
        $id = $request['GET']['id'];
        $found = false;
        $quantity = 0;

        for ($index = 0; $index < count($carts); $index++) {
            if ($carts[$index]['id'] == $id) {
                if ($carts[$index]['quantity'] + $request['GET']['quantity'] > 0) {
                    $carts[$index] = ['id' => $id, 'quantity' => $carts[$index]['quantity'] + $request['GET']['quantity']];
                }
                $quantity = $carts[$index]['quantity'];
                $found = true;
                break;
            }
        }
        if (!$found) {
            array_push($carts, ['id' => $id, 'quantity' => 1]);
            $quantity = 1;
        }

        $_SESSION['carts'] = $carts;
        return $quantity;
    }

    public function remove($request) {
        $carts = isset($_SESSION['carts']) ? $_SESSION['carts'] : [];
        $id = $request['GET']['id'];
        for ($index = 0; $index < count($carts); $index++) {
            if ($carts[$index]['id'] == $id)
                unset($carts[$index]);
        }
        $_SESSION['carts'] = $carts;
        return true;
    }

    public function submit($request) {
        $post = $request['POST'];
        $carts = $request['SESSION']['carts'];

        $name = $post['name'];
        $phone = $post['phone'];
        $address = $post['address'];
        $email = isset($post['email']) ? $post['email'] : '';
        $ids = [];
        $quantities = [];
        foreach ($carts as $cart) {
            array_push($ids, $cart['id']);
            array_push($quantities, $cart['quantity']);
        }

        $cart_model = new Cart();
        $cart_model->add_cart($name, $phone, $email, $address, $ids, $quantities);

        $_SESSION['carts'] = [];

        return true;
    }
}