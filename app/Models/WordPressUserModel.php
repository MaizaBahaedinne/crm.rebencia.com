<?php namespace App\Models;

use CodeIgniter\Model;

class WordPressUserModel extends Model
{
    protected $table = 'wp_Hrg8P_users';
    protected $primaryKey = 'ID';
    protected $allowedFields = ['user_login', 'user_pass', 'user_email'];

    // Auth via password
    public function verifyUser($username, $password)
    {
        $user = $this->where('user_login', $username)->first();
        if (!$user) return false;

        // WordPress utilise un hash spécifique
        require_once APPPATH.'ThirdParty/PasswordHash.php';
        $wp_hasher = new \PasswordHash(8, TRUE);
        return $wp_hasher->CheckPassword($password, $user['user_pass']) ? $user : false;
    }
}
