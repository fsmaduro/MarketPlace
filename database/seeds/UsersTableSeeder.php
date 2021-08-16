<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \DB::table('users')->insert(
        //     [
        //         'name' => 'admin',
        //         'email' => 'admin@mail.com',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('123456'), // password
        //         'remember_token' => Str::random(10),
        //     ]
        // );

        factory(\App\User::class, 40)->create()->each(function($user){
            $user->store()->save(factory(\App\Store::class)->make());
        });
    }
}
