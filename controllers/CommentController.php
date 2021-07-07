<?php
include_once 'Controller.php';
include_once __DIR__ . '/../models/Comment.php';

class CommentController extends Controller {
    public function censore($request) {
        if (!$this->authorize())
            return false;

        $get = $request['GET'];

        $model = new Comment();
        $model->id = $get['id'];

        $model->censore(true);

        return true;
    }

    public function destroy($request) {
        if (!$this->authorize())
            return false;
        
        $get = $request['GET'];

        $model = new Comment();
        $model->id = $get['id'];

        $model->destroy(true);

        return true;
    }
}