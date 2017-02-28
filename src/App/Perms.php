<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;

/**
 * Class Perms
 * @package Crip\Filesys\App
 */
class Perms implements ICripObject
{
    /**
     * Determines if current user is allowed to read file by its permissions
     * @param string|int $file_perms
     * @return boolean
     */
    public static function canRead($file_perms)
    {
        $is_allowed = static::readFilePermsFrom($file_perms, 3);

        return $is_allowed;
    }

    /**
     * Determines if current user is allowed to write/modify by file permissions
     * @param string|int $file_perms
     * @return boolean
     */
    public static function canWrite($file_perms)
    {
        $is_allowed = static::readFilePermsFrom($file_perms, 4);

        return $is_allowed;
    }

    /**
     * Determines if current user is allowed to delete by file permissions
     * @param string|int $file_perms
     * @return boolean
     */
    public static function canDelete($file_perms)
    {
        $is_allowed = static::readFilePermsFrom($file_perms, 5);

        return $is_allowed;
    }

    /**
     * @param $file_path
     * @return array
     */
    public static function getFilePerms($file_path)
    {
        $file_perms = fileperms($file_path);
        $perms_arr = static::readPerms($file_perms, false);

        return $perms_arr;
    }

    /**
     * @param string|int $file_perms
     * @param int $default_key
     * @return boolean
     */
    private static function readFilePermsFrom($file_perms, $default_key)
    {
        // is is string, use as path to the file
        if (is_string($file_perms)) {
            $file_perms = fileperms($file_perms);
        }
        $perms = static::readPerms($file_perms);
        $key_to_read = static::readKey($default_key);
        $is_allowed = $perms[$key_to_read];

        return $is_allowed;
    }

    /**
     * Get right key identifier
     * @param int $default
     * @return int
     */
    private static function readKey($default)
    {
        if (\Auth::check()) {
            return $default - 3;
        }

        return $default;
    }

    /**
     * Get octal permission number from booleans
     * @param array|bool|true [$auth_can_read]
     * @param bool|true [$auth_can_write]
     * @param bool|true [$auth_can_delete]
     * @param bool|true [$any_can_read]
     * @param bool|false [$any_can_write]
     * @param bool|false [$any_can_delete]
     *
     * @return int
     */
    private static function getPerms()
    {
        $default = [1, 1, 1, 1, 0, 0];
        if (func_num_args() === 1 && is_array(func_get_arg(0))) {
            $user_defined = func_get_arg(0);
        } else {
            $user_defined = func_get_args();
        }
        foreach ($default as $key => $val) {
            if (isset($user_defined[$key])) {
                $default[$key] = $user_defined[$key];
            }
        }
        $params = array_map(function ($x) {
            return !!$x; // convert each value to boolean
        }, $default);
        // owner full rights
        $perms = 0x0100 + 0x0080 + 0x0040;

        // auth user perms
        if ($params[0]) {
            $perms += 0x0020;
        }

        if ($params[1] && $params[0]) {
            $perms += 0x0010;
        }

        if ($params[2] && $params[0]) {
            $perms += 0x0008;
        }

        // any user perms
        if ($params[3]) {
            $perms += 0x0004;
        }

        if ($params[4] && $params[3]) {
            $perms += 0x0002;
        }

        if ($params[5] && $params[3]) {
            $perms += 0x0001;
        }

        return $perms;
    }

    /**
     * Convert octal permission to boolean array
     * @param int $perms
     * @param bool $indexed
     * @return array
     */
    private static function readPerms($perms, $indexed = true)
    {
        $result = [
            'auth_can_read' => !!($perms & 0x0020),
            'auth_can_write' => !!($perms & 0x0010),
            'auth_can_delete' => !!($perms & 0x0008),
            'any_can_read' => !!($perms & 0x0004),
            'any_can_write' => !!($perms & 0x0002),
            'any_can_delete' => !!($perms & 0x0001)
        ];

        if (!$indexed) {
            return $result;
        }

        return array_values($result);
    }
}