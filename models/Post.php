<?php
include_once 'Model.php';

class Post extends Model {
    public string $table = 'posts';
    public string $primary_key = 'id';
    public string $columns = 'id, glass_id, name, phone, email, rating, content, time, is_cencored';

    public function add_comment(string $name, string $phone, string $email, string $content) : int {
        $date = date('Y-m-d H:i:s');
        return $this->db->insert('comments', 'post_id, name, phone, email, content, time, is_censored', "$this->id, $name, $phone, $email, $content, $date, 0");
    }

    public function add_admin_comment(string $user_id, string $content) : int {
        $date = date('Y-m-d H:i:s');
        return $this->db->insert('comments', 'post_id, user_id, content, time, is_censored', "$this->id, $user_id, $content, $date, 1");
    }

    public function censore(bool $passed = true) {
        $this->db->update('posts', 'is_censored', "$passed", "id = $this->id");
    }

    public function destroy() {
        $this->db->delete('comments', "post_id = $this->id");
        $this->db->delete('posts', "id = $this->id");
    }

    public function get_comments($is_admin) : mysqli_result {
        $command = "SELECT comments.id AS id, user_id, name, content, users.first_name AS admin_first_name, users.last_name AS admin_last_name, time, is_censored FROM comments LEFT JOIN users ON comments.user_id = users.id WHERE post_id = $this->id";
        if (!$is_admin)
            $command .= " AND is_censored = 1";
        return $this->db->query($command);
    }
}