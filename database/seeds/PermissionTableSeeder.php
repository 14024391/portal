<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            ['group' => 'user', 'name' => 'view_users'],
            ['group' => 'user','name' => 'create_user'],
            ['group' => 'user','name' => 'update_user'],
            ['group' => 'user', 'name' => 'delete_user'],
            ['group' => 'permission', 'name' => 'view_permissions'],
            ['group' => 'permission', 'name' => 'update_permission'],
            ['group' => 'vehicle', 'name' => 'view_vehicle'],
            ['group' => 'vehicle', 'name' => 'create_vehicle'],
            ['group' => 'vehicle', 'name' => 'update_vehicle'],
            ['group' => 'vehicle', 'name' => 'delete_vehicle'],
            ['group' => 'autotrader importer', 'name' => 'create_import_file'],
            ['group' => 'autotrader importer', 'name' => 'process_import_file'],
        ]);

        $permissions = Permission::all();
        foreach ($permissions as $permission){
            DB::table('permission_role')->insert(['role_id' => 1, 'permission_id' => $permission->id]);
        }
    }
}
