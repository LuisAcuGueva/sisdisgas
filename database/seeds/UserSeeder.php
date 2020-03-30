<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;
		$usertype_id = DB::table('usertype')->where('name', '=', 'ADMINISTRADOR PRINCIPAL')->first()->id;
		DB::table('user')->insert(array(
				'login'          => 'admin',
				'password'       => Hash::make('123456'),
				'state'         => 'H',
				'usertype_id' => $usertype_id,
				'person_id' 	 => 1,
				'created_at'     => $now,
				'updated_at'     => $now
			));
    }
}
