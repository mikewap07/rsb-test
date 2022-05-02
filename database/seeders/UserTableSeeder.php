<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'name'		    => 'Mike Conde',
            'email'		    => 'mike.conde28@gmail.com',
            'password'	    => Hash::make('12345'),
        ]);

        User::create([
            'name'		    => 'RSB Acc',
            'email'		    => 'rsb@gmail.com',
            'password'	    => Hash::make('12345'),
        ]);
    }
}
