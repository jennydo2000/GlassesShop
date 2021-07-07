<?php
include_once 'Model.php';

class Comment extends Model {
    public string $table = 'posts';
    public string $primary_key = 'id';
    public string $columns = 'id, user_id, post_id, name, phone, email, content, time, is_cencored';

    public function censore(bool $passed = true) {
        $this->db->update('comments', 'is_censored', "$passed", "id = $this->id");
    }

    public function destroy() {
        $this->db->delete('comments', "id = $this->id");
    }
}