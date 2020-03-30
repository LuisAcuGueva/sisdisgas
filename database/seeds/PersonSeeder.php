<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = new DateTime;

		DB::table('person')->insert(array(
				array(
					'lastname' => 'Principal',
					'firstname' => 'Administrador',
					'created_at' => $now,
					'updated_at' => $now
				)
			));
    }
}
