<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('admins')->insert([
            'username' => 'superadmin',
            'password' => password_hash('123456', PASSWORD_DEFAULT, ['cost' => 12]),
            'email' => '122222222@qq.com',
            'tel' => '18512557921',
            'status' => 1,
            'company_dwdm' => 100000,
            'dwjb' => 2,
            'register_if_check' => 1,
        ]);
    }
}
