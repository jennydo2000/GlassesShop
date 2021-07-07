<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Glass.php';
include_once __DIR__ . '/../models/Post.php';

class GlassController extends Controller {
    public function index($request) {
        $get = $request['GET'];

        $model = new Glass();

        $keyword = isset($get['keyword']) ? $get['keyword'] : '';
        $where = '';
        if (isset($get['keyword']));
            $where = "name LIKE '%$keyword%' OR trademark = '%$keyword%'";
        $data = $model->index('id, name, price, discount', '', $where);
        $this->view('index', ['data' => $data, 'keyword' => $keyword]);
    }

    public function show($request) {
        $get = $request['GET'];

        $is_admin = $this->authorize();

        $model = new Glass();
        $model->id = $get['id'];
        $data = $model->show('id, name, trademark, color, quantity, price, discount, is_opened');
        $data['rating'] = $model->get_rating();

        $posts_data = [];
        $posts = $model->get_posts($is_admin);
        while($post = $posts->fetch_assoc()) {
            $post_model = new Post();
            $post_model->id = $post['id'];
            $comments = $post_model->get_comments($is_admin);
            array_push($posts_data, ['post' => $post, 'comments' => $comments]);
        }
        $data['posts'] = $posts_data;
        $data['posts_count'] = $model->posts_count();
        $this->view('show', $data);
    }

    public function store($request) {
        if (!$this->authorize())
            return false;
        
        $post = $request['POST'];

        $name = str_replace(',', '&#44;', $post['name']);
        $trademark = str_replace(',', '&#44;', $post['trademark']);
        $color = isset($post['color']) ? substr($post['color'], 1) : 0;
        $quantity = isset($post['quantity']) ? $post['quantity'] : 0;
        $price = isset($post['price']) ? $post['price'] : 0;
        $discount = isset($post['discount']) ? $post['discount'] : 0;
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $is_opened = $post['is_opened'];

        $id = $this->db->insert('glasses', 'name, trademark, color, quantity, price, discount, is_opened', "$name, $trademark, $color, $quantity, $price, $discount, $is_opened");
        
        if ($image != null) {
            move_uploaded_file($image['tmp_name'], "public/images/glasses/glass$id.png");
        }
        
        return true;
    }

    public function update($request) {
        if (!$this->authorize())
            return false;
        
        $post = $request['POST'];

        $id =  $post['id'];
        $name = str_replace(',', '&#44;', $post['name']);
        $trademark = str_replace(',', '&#44;', $post['trademark']);
        $color = isset($post['color']) ? substr($post['color'], 1) : 0;
        $quantity = isset($post['quantity']) ? $post['quantity'] : 0;
        $price = isset($post['price']) ? $post['price'] : 0;
        $discount = isset($post['discount']) ? $post['discount'] : 0;
        $image = isset($_FILES['image']) ? $_FILES['image'] : null;
        $is_opened = $post['is_opened'];

        $this->db->update('glasses', 'name, trademark, color, quantity, price, discount, is_opened', "$name, $trademark, $color, $quantity, $price, $discount, $is_opened", "id = $id");
        
        if ($image != null) {
            move_uploaded_file($image['tmp_name'], "public/images/glasses/glass$id.png");
        }

        return true;
    }

    public function destroy($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $id = $get['id'];

        $this->db->delete('comments', "id IN (SELECT id FROM comments WHERE post_id IN (SELECT id FROM posts WHERE glass_id = $id))");
        $this->db->delete('posts', "id IN (SELECT id FROM posts WHERE glass_id = $id)");
        $this->db->delete('glasses', "id = $id");

        unlink("public/images/glasses/glass$id.png");

        return true;
    }

    public function add_post($request) {        
        $get = $request['GET'];
        $post = $request['POST'];

        $model = new Glass();
        $model->id = $get['id'];

        $name = str_replace(',', '&#44;', $post['name']);
        $phone = isset($post['phone']) ? $post['phone'] : '';
        $email = isset($post['email']) ? str_replace(',', '&#44;', $post['email']) : '';
        $rating = $post['rating'];
        $content = str_replace(',', '&#44;', $post['content']);

        $model->add_post($name, $phone, $email, $rating, $content);
        return true;
    }
}