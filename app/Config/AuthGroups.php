<?php

namespace Config;

use CodeIgniter\Shield\Config\AuthGroups as ShieldAuthGroups;

class AuthGroups extends ShieldAuthGroups
{
    /**
     * --------------------------------------------------------------------------
     * Default Group
     * --------------------------------------------------------------------------
     *
     * The group that a newly registered user is added to, if no other group
     * is specified.
     */
    public string $defaultGroup = 'user';

    /**
     * --------------------------------------------------------------------------
     * Groups
     * --------------------------------------------------------------------------
     *
     * An associative array of the available groups in the system, where the keys
     * are the group names and the values are arrays of group info.
     *
     * For most projects, you only need to check only that a user is in a group,
     * but the 'title' and 'description' keys are used for display purposes.
     */
    public array $groups = [
        'superadmin' => [
            'title'       => 'Super Admin',
            'description' => 'Complete control of the site.',
        ],
        'admin' => [
            'title'       => 'Admin',
            'description' => 'Day to day administrators of the site.',
        ],
        'developer' => [
            'title'       => 'Developer',
            'description' => 'Site programmers.',
        ],
        'user' => [
            'title'       => 'User',
            'description' => 'General users of the site. Often customers.',
        ],
        'guest' => [
            'title'       => 'Guest',
            'description' => 'Guest users of the site.',
        ],
        'asuhan' => [
            'title'       => 'Asuhan',
            'description' => 'Anak Asuhan Yayasan.',
        ],
        'beta' => [
            'title'       => 'Beta User',
            'description' => 'Has access to beta-level features.',
        ],
    ];

    /**
     * --------------------------------------------------------------------------
     * Permissions
     * --------------------------------------------------------------------------
     *
     * The available permissions in the system. Each system is defined where
     * the key is the permission name and the value is a human-readable title.
     *
     * Does not support nested permissions, but you can use dotted notation
     * to indicate scope (e.g. users.view).
     */
    public array $permissions = [
        'admin.access'        => 'Can access the admin area',
        'users.manage-admins' => 'Can manage other admins',
        'users.create'        => 'Can create new non-admin users',
        'users.edit'          => 'Can edit existing non-admin users',
        'users.delete'        => 'Can delete existing non-admin users',
        'beta.access'         => 'Can access beta-level features',
    ];

    /**
     * --------------------------------------------------------------------------
     * Permissions Matrix
     * --------------------------------------------------------------------------
     *
     * Maps permissions to groups.
     */
    public array $matrix = [
        'superadmin' => [
            'admin.*',
            'users.*',
            'beta.*',
        ],
        'admin' => [
            'admin.access',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'developer' => [
            'admin.access',
            'users.manage-admins',
            'users.create',
            'users.edit',
            'users.delete',
            'beta.access',
        ],
        'user' => [],
        'beta' => [
            'beta.access',
        ],
    ];
}
