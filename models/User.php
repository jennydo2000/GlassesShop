<?php
include_once 'Model.php';

class User extends Model {
    public string $table = 'users';
    public string $primary_key = 'id';
    public string $columns = 'id, username, first_name, last_name, phone, address, token';

    public function login(string $username, string $password) : string {
        $user = $this->db->query("SELECT * FROM users WHERE username = '$username' AND pass = '$password'");
        if ($user->num_rows == 0)
            return false;
        $token = bin2hex(openssl_random_pseudo_bytes(8));
        $this->db->update('users', 'token', $token, "username = '$username' AND pass = '$password'");
        return $token;
    }
}