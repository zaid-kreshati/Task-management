<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaderRole = Role::create(['name' => 'leader']);
        $usertRole = Role::create(['name' => 'user']);

        // Define Permissions
        $leaderPeermission = [ 'task.create' , 'task.delete', 'task.update' ,'subtask.create' , 'subtask.delete', 'subtask.update' ,'task.search',
                            'category.index', 'category.create' , 'category.delete', 'category.update'];

        $userPermissions = ['task.search' , 'task.index' , 'task.comment.add' , 'task.comment.update' , 'task.comment.delete' ,
                            'subtask.comment.add' , 'subtask.comment.update' , 'subtask.comment.delete'];


        // Create Permissions
        $allPermissions = array_unique(array_merge($leaderPeermission, $userPermissions));

        foreach ($allPermissions as $permissionName) {
            Permission::findOrCreate($permissionName, 'web'); // Also for API
        }

        // Assign Permissions to Roles
        $leaderRole->syncPermissions($leaderPeermission); // Admin gets all permissions
        $usertRole->syncPermissions($userPermissions);

        // Create users and assign roles
        // Leader
        $leader = User::factory()->create([
            'name' => 'malek',
            'email' => 'malek@leader.com',
            'password' => bcrypt('password'),
        ]);
        $leader->assignRole($leaderRole);

          // leader2
          $leader2 = User::factory()->create([
            'name' => 'amin',
            'email' => 'amin@leader.com',
            'password' => bcrypt('password'),
        ]);
        $leader2->assignRole($leaderRole);

        // user
        $User = User::factory()->create([
            'name' => 'zaid',
            'email' => 'zaid@user.com',
            'password' => bcrypt('password'),
        ]);
        $User->assignRole($usertRole);

      
    }

}
