<?php

namespace opensrs\OMA;

use opensrs\OMA;
use opensrs\Exception;

// command: rename_user
// Rename a user 

class RenameUser
{
    public static function call($data)
    {
        if (self::validate($data)) {
            return OMA::send_cmd('rename_user', $data);
        }
    }

    public static function validate($data)
    {
        if (empty($data['user']) || empty($data['new_name'])) {
            throw new Exception('oSRS Error - User/IDs required');
        } else {
            return true;
        }
    }
}
