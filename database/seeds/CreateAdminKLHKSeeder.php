<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class CreateAdminKLHKSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'admin_name'    => 'KLHK',
            'email'         => 'klhk@gmail.com',
            'role_id'       => 1,
            'password'      => bcrypt('123456'),
            'is_klhk'       => true
        ]);
    }
}
