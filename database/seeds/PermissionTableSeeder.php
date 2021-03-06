<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    protected $permissions = [
        [
            "id" => 1,
            "name" => "system",
            "label" => "系统管理",
            "redirect" => "/system/user",
            "path" => "/system",
            "meta" => [
                "title" => "系统管理",
                "icon" => "nested",
            ],
            "parent" => 0,
            "display" => 1,
            "component" => "Layout",
            "sort" => "0",
            "is_api" => 0,
        ],
        [
            "id" => 2,
            "name" => "users.index",
            "label" => "用户管理",
            "redirect" => null,
            "path" => "user",
            "meta" => [
                "title" => "用户管理",
                "icon" => "user",
            ],
            "parent" => 1,
            "display" => 1,
            "component" => "system/user/index",
            "sort" => "0",
            "is_api" => 0,
        ],
        [
            "id" => 3,
            "name" => "users.store",
            "label" => "新增用户",
            "redirect" => null,
            "path" => "api/system/users",
            "meta" => [
                "title" => "新增用户",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 4,
            "name" => "users.update",
            "label" => "用户修改",
            "redirect" => null,
            "path" => "api/system/users/{user}",
            "meta" => [
                "title" => "用户修改",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 5,
            "name" => "users.show",
            "label" => "用户信息",
            "redirect" => null,
            "path" => "api/system/users/{user}",
            "meta" => [
                "title" => "用户信息",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 6,
            "name" => "users.destroy",
            "label" => "用户删除",
            "redirect" => null,
            "path" => "api/system/users/{user}",
            "meta" => [
                "title" => "用户删除",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 7,
            "name" => "users.freeze",
            "label" => "用户冻结",
            "redirect" => null,
            "path" => "api/system/users/{id}/freeze",
            "meta" => [
                "title" => "用户冻结",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 8,
            "name" => "users.reset",
            "label" => "用户重置",
            "redirect" => null,
            "path" => "api/system/users/{id}/reset",
            "meta" => [
                "title" => "用户重置",
                "icon" => null,
            ],
            "parent" => 2,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 9,
            "name" => "roles.index",
            "label" => "角色管理",
            "redirect" => null,
            "path" => "roles",
            "meta" => [
                "title" => "角色管理",
                "icon" => "peoples",
            ],
            "parent" => 1,
            "display" => 1,
            "component" => "system/role/index",
            "sort" => "0",
            "is_api" => 0,
        ],
        [
            "id" => 10,
            "name" => "roles.show",
            "label" => "角色信息",
            "redirect" => null,
            "path" => "api/system/roles/{role}",
            "meta" => [
                "title" => "角色信息",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 11,
            "name" => "roles.store",
            "label" => "角色新增",
            "redirect" => null,
            "path" => "api/system/roles",
            "meta" => [
                "title" => "角色新增",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 12,
            "name" => "roles.freeze",
            "label" => "角色冻结",
            "redirect" => null,
            "path" => "api/system/roles/{id}/freeze",
            "meta" => [
                "title" => "角色冻结",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 13,
            "name" => "roles.reset",
            "label" => "角色重置",
            "redirect" => null,
            "path" => "api/system/roles/{id}/reset",
            "meta" => [
                "title" => "角色重置",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 14,
            "name" => "roles.update",
            "label" => "角色修改",
            "redirect" => null,
            "path" => "api/system/roles/{role}",
            "meta" => [
                "title" => "角色修改",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 15,
            "name" => "roles.destroy",
            "label" => "角色删除",
            "redirect" => null,
            "path" => "api/system/roles/{role}",
            "meta" => [
                "title" => "角色删除",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 16,
            "name" => "roles.role_permission",
            "label" => "角色权限",
            "redirect" => null,
            "path" => "api/system/role_permission/{id}",
            "meta" => [
                "title" => "角色权限",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 17,
            "name" => "roles.role_dict",
            "label" => "角色列表",
            "redirect" => null,
            "path" => "api/system/role_dict",
            "meta" => [
                "title" => "角色列表",
                "icon" => null,
            ],
            "parent" => 9,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 18,
            "name" => "permissions.index",
            "label" => "权限管理",
            "redirect" => null,
            "path" => "permissions",
            "meta" => [
                "title" => "权限管理",
                "icon" => "tree",
            ],
            "parent" => 1,
            "display" => 1,
            "component" => "system/permission/index",
            "sort" => "0",
            "is_api" => 0,
        ],
        [
            "id" => 19,
            "name" => "permissions.store",
            "label" => "权限新增",
            "redirect" => null,
            "path" => "api/system/permissions",
            "meta" => [
                "title" => "权限新增",
                "icon" => null,
            ],
            "parent" => 18,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 20,
            "name" => "permissions.node_drop",
            "label" => "权限排序",
            "redirect" => null,
            "path" => "api/system/role_permission/{id}",
            "meta" => [
                "title" => "权限排序",
                "icon" => null,
            ],
            "parent" => 18,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 21,
            "name" => "permissions.update",
            "label" => "权限修改",
            "redirect" => null,
            "path" => "api/system/permissions/{permission}",
            "meta" => [
                "title" => "权限修改",
                "icon" => null,
            ],
            "parent" => 18,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 22,
            "name" => "permissions.destroy",
            "label" => "权限删除",
            "redirect" => null,
            "path" => "api/system/permissions/{permission}",
            "meta" => [
                "title" => "权限删除",
                "icon" => null,
            ],
            "parent" => 18,
            "display" => 0,
            "component" => null,
            "sort" => "0",
            "is_api" => 1,
        ],
        [
            "id" => 23,
            "name" => "content.index",
            "label" => "内容管理",
            "redirect" => null,
            "path" => "content",
            "meta" => [
                "title" => "内容管理",
                "icon" => "list",
            ],
            "parent" => 1,
            "display" => 1,
            "component" => "system/content/index",
            "sort" => "0",
            "is_api" => 0,
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permissions as $permission) {
            \App\Models\System\Permission::create($permission);
        }
    }
}
