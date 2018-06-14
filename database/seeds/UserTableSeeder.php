<?php

use Illuminate\Database\Seeder;
Use App\Models\Role;
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
        $role_admin = Role::where('name','superadmin')->first();
        $role_manager = Role::where('name','manager')->first();
        $role_stock = Role::where('name','stock')->first();


        $user = new User();
        $user->name = 'Ashutosh Verma';
        $user->email = 'ashutosh@newgenray.com';
        $user->password = bcrypt('admin@a&m#18');
        $user->timezone = 'Europe/London';
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Lamin Singhateh';
        $user->email = 'lamin@amcommercials.com';
        $user->password = bcrypt('lamin');
        $user->timezone = 'Europe/London';
        $user->save();
        $user->roles()->attach($role_admin);

        $user = new User();
        $user->name = 'Martina';
        $user->email = 'martina@amcommercials.com';
        $user->password = '$2y$10$pO97kjUN/kQMbCueKzLYQ.QbriY7intfq8lMLM2759VpI1qPJOHfC';
        $user->timezone = 'Europe/London';
        $user->save();
        $user->roles()->attach($role_stock);

        $user = new User();
        $user->name = 'Vlad Sarasovs';
        $user->email = 'media@amcommercials.com';
        $user->password = '$2y$10$np60lKdI7wclK/JJGnTJquq7ugg6bj8FDsZUCiLsQYpXOmaKvwYEm';
        $user->timezone = 'Europe/London';
        $user->save();
        $user->roles()->attach($role_stock);
    }
}
