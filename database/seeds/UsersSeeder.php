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
	    Schema::disableForeignKeyConstraints();

        User::truncate();

        collect( [
	        [
		        'name' => 'John Doe',
		        'username' => 'johndoe',
		        'email' => 'john@example.com',
		        'password' => bcrypt('password'),
	        ],
	        [
		        'name' => 'Indiana Jones',
		        'username' => 'rotla1981',
		        'email' => 'indy@example.com',
		        'password' => bcrypt('password')
	        ],
	        [
		        'name' => 'Ben Solo',
		        'username' => 'KyloRen',
		        'email' => 'kylo@example.com',
		        'password' => bcrypt('password')
	        ],
	        [
		        'name' => 'Marty McFly',
		        'username' => '121gigawatts',
		        'email' => 'calvin@example.com',
		        'password' => bcrypt('password')
	        ]
        ] )->each( function( $user )
        {
	        factory( User::class )->create(
		        [
			        'name'      => $user['name'],
			        'username'  => $user['username'],
			        'email'     => $user['email'],
			        'password'  => bcrypt('password')
		        ]
	        );
        } );

	    Schema::enableForeignKeyConstraints();
    }
}
