<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        //CREANDO PERMISOS
        //-----------------------------------------------------------------------------------
        //roles
        $resource = 'roles';
        $permisos = collect([
            ['name' => "Ver {$resource}"],
            ['name' => "Crear {$resource}"],
            ['name' => "Actualizar {$resource}"],
            ['name' => "Eliminar {$resource}"],
        ]);
        $permisos->each(function ($permiso) use ($resource) {
            $permiso['group'] = $resource;
            if (!\Spatie\Permission\Models\Permission::where('name', $permiso['name'])->exists())
                Permission::create($permiso);
        });

        //Asignando permisos
        Role::findByName('Superadministrador')->givePermissionTo($permisos);
        Role::findByName('Administrador')->givePermissionTo($permisos);
        //-----------------------------------------------------------------------------------
        
    }
}
