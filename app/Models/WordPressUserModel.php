
<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

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
    /**
     * Récupère le(s) rôle(s) WordPress d'un utilisateur par son ID
     * @param int $userId
     * @return array|null
     */
    public function getUserRoles($userId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('wp_Hrg8P_usermeta');
        $row = $builder->where('user_id', $userId)
            ->where('meta_key', 'wp_capabilities')
            ->get()->getRow();
        if (!$row) return null;
        $metaValue = $row->meta_value;
        // Désérialiser la valeur PHP stockée par WordPress
        $roles = @unserialize($metaValue);
        if (!is_array($roles)) return null;
        return array_keys(array_filter($roles));
    }
}
