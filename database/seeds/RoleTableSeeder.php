<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class RoleTableSeeder extends Seeder
{
    protected $roles = [
        ''
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = \App\Models\Role::create([
            'name' => '超级管理员',
            'desc' => Str::random(100),
            'active' => 1,
            'created_at' => \Illuminate\Support\Carbon::now(),
            'updated_at' => \Illuminate\Support\Carbon::now(),
        ]);

        $ids = [];

        for ($i = 1; $i++; $i <= 22) {
            $ids[] = $i;
        }

        $role->permissions()->attach($ids);
    }
}
