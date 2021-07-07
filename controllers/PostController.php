<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Post.php';

class PostController extends Controller {
    public function add_comment($request) {
        $get = $request['GET'];
        $post = $request['POST'];

        $model = new Post();
        $model->id = $get['id'];

        $name = isset($post['name']) ? str_replace(',', '&#44;', $post['name']) : '';
        $phone = isset($post['phone']) ? $post['phone'] : '';
        $email = isset($post['email']) ? str_replace(',', '&#44;', $post['email']) : '';
        $content = str_replace(',', '&#44;', $post['content']);

        if ($this->authorize())
            $model->add_admin_comment($this->get_user()['id'], $content);
        else
            $model->add_comment($name, $phone, $email, $content);
        return true;
    }

    public function censore($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $model = new Post();
        $model->id = $get['id'];

        $model->censore(true);

        return true;
    }

    public function destroy($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $model = new Post();
        $model->id = $get['id'];

        $model->destroy(true);

        return true;
    }
}