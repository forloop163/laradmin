<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $arr = array(
        130, 131, 132, 133, 134, 135, 136, 137, 138, 139,
        144, 147,
        150, 151, 152, 153, 155, 156, 157, 158, 159,
        176, 177, 178,
        180, 181, 182, 183, 184, 185, 186, 187, 188, 189,
    );

    return [
        'username' => 'admin',
        'mobile' => $arr[array_rand($arr)] . mt_rand(1000, 9999) . mt_rand(1000, 9999),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});
