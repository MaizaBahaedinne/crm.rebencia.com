<?php
/**
 * Portable PHP password hashing framework.
 * @package phpass
 * @link https://www.openwall.com/phpass/
 *
 * This is the PasswordHash class used by WordPress and many other PHP projects.
 */

if (!class_exists('PasswordHash')) {
    class PasswordHash {
        var $itoa64;
        var $iteration_count_log2;
        var $portable_hashes;
        var $random_state;

        function __construct($iteration_count_log2, $portable_hashes)
        {
            $this->itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

            if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
                $iteration_count_log2 = 8;
            $this->iteration_count_log2 = $iteration_count_log2;

            $this->portable_hashes = $portable_hashes;

            $this->random_state = microtime() . uniqid(rand(), TRUE);
        }

        function get_random_bytes($count)
        {
            $output = '';
            if (function_exists('random_bytes')) {
                try {
                    $output = random_bytes($count);
                } catch (Exception $e) {
                    $output = '';
                }
            }
            if (strlen($output) < $count) {
                $output = '';
                for ($i = 0; $i < $count; $i += 16) {
                    $this->random_state = md5(microtime() . $this->random_state);
                    $output .= pack('H*', md5($this->random_state));
                }
                $output = substr($output, 0, $count);
            }
            return $output;
        }

        function encode64($input, $count)
        {
            $output = '';
            $i = 0;
            do {
                $value = ord($input[$i++]);
                $output .= $this->itoa64[$value & 0x3f];
                if ($i < $count)
                    $value |= ord($input[$i]) << 8;
                $output .= $this->itoa64[($value >> 6) & 0x3f];
                if ($i++ >= $count)
                    break;
                if ($i < $count)
                    $value |= ord($input[$i]) << 16;
                $output .= $this->itoa64[($value >> 12) & 0x3f];
                if ($i++ >= $count)
                    break;
                $output .= $this->itoa64[($value >> 18) & 0x3f];
            } while ($i < $count);
            return $output;
        }

        function gensalt_private($input)
        {
            $iteration_count_log2 = min($this->iteration_count_log2 + ((PHP_VERSION >= '5') ? 5 : 3), 30);
            $output = '$P$';
            $output .= $this->itoa64[min($iteration_count_log2 + 5, 30)];
            $output .= $this->encode64($input, 6);
            return $output;
        }

        function crypt_private($password, $setting)
        {
            $output = '*0';
            if (substr($setting, 0, 2) == '*0')
                $output = '*1';

            $id = substr($setting, 0, 3);
            if ($id != '$P$' && $id != '$H$')
                return $output;

            $count_log2 = strpos($this->itoa64, $setting[3]);
            if ($count_log2 < 7 || $count_log2 > 30)
                return $output;

            $count = 1 << $count_log2;
            $salt = substr($setting, 4, 8);
            if (strlen($salt) != 8)
                return $output;

            $hash = md5($salt . $password, TRUE);
            for ($i = 0; $i < $count; $i++)
                $hash = md5($hash . $password, TRUE);

            $output = substr($setting, 0, 12);
            $output .= $this->encode64($hash, 16);

            return $output;
        }

        function CheckPassword($password, $stored_hash)
        {
            $hash = $this->crypt_private($password, $stored_hash);
            if ($hash[0] == '*')
                $hash = crypt($password, $stored_hash);
            return $hash === $stored_hash;
        }
    }
}
