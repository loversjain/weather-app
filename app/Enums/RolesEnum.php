<?php

namespace App\Enums;

/**
 * Class RolesEnum
 *
 * Enum class for managing user roles.
 *
 * @package App\Enums
 */
enum RolesEnum:int 
{
    /**
     * Admin role.
     */
    case Admin = 1;

    /**
     * Buyer role.
     */
    case Buyer = 2;

    /**
     * Get the role name.
     *
     * @param self $role The role enum.
     * @return string Returns the role name.
     */
    public static function getRoleName(self $role): string
    {
        return match ($role) {
            self::Admin => "ADMIN",
            self::Buyer => "BUYER",
        };
    }
}
