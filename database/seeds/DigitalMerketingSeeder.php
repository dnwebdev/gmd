<?php

use Illuminate\Database\Seeder;

class DigitalMerketingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\Models\Role();
        $role->role_name='Digital Marketing';
        $role->role_slug = \Illuminate\Support\Str::slug($role->role_name);
        $role->save();

        $digitalMarketing = new \App\Models\Admin();
        $digitalMarketing->admin_name='Digital Marketing';
        $digitalMarketing->email='digitalmarketing@gomodo.tech';
        $digitalMarketing->password = bcrypt('password');
        $digitalMarketing->role_id = $role->id;
        $digitalMarketing->save();

    }
}
