<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'bookings owner']);
        $role->givePermissionTo(Permission::create(['name' => 'edit own bookings']));
        $role->givePermissionTo(Permission::create(['name' => 'view own bookings']));
        $role->givePermissionTo(Permission::create(['name' => 'create bookings']));
    }
}