<?php
include_once 'Model.php';

class Glass extends Model {
    public string $table = 'glasses';
    public string $primary_key = 'id';
    public string $columns = 'id, name, trademark, color, quantity, price, discount, is_opened';

    public function add_quantity(int $number) {
        $storing_number = $this->db->query("SELECT quantity FROM glasses WHERE id = $this->id")->fetch_assoc()['quantity'];
        $new_number = $number + $storing_number;
        $this->quantity = $new_number;
        $this->update();
        $this->find($this->id);
    }

    public function add_post(string $name, string $phone, string $email, int $rating, string $content) : int {
        $date = date('Y-m-d H:i:s');
        return $this->db->insert('posts', 'glass_id, name, phone, email, rating, content, time, is_censored', "$this->id, $name, $phone, $email, $rating, $content, $date, 0");
    }

    public function get_rating() : float {
        $score = $this->db->query("SELECT AVG(rating) AS rating FROM posts WHERE glass_id = $this->id AND is_censored = 1")->fetch_assoc()['rating'];
        if ($score != null)
            return $score;
        else
            return 0;
    }

    public function get_posts($is_admin) : mysqli_result {
        $command = "SELECT * FROM posts WHERE glass_id = $this->id";
        if (!$is_admin)
            $command .= " AND is_censored = 1";
        return $this->db->query($command);
    }

    public function posts_count() : int {
        return $this->db->query("SELECT COUNT(id) AS number FROM posts WHERE glass_id = $this->id AND is_censored = 1")->fetch_assoc()['number'];
    }
}
//Chỉnh lại value insert update. Thêm dấu , là bị lỗi