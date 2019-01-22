<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        factory( User::class )
            ->create([
            	'name' => 'John Doe',
	            'username' => 'johndoe',
	            'email' => 'john@example.com',
	            'password' => bcrypt('password'),
            ]);
    }
}
