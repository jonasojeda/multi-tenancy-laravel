<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Stancl\Tenancy\Exceptions\TenantDatabaseAlreadyExistsException;

class TenantDomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $tenant1 = Tenant::create(['id' => 'foo']);
            $tenant1->domains()->create(['domain' => 'foo.localhost']);
        } catch (TenantDatabaseAlreadyExistsException $e) {
            // Manejar la excepción si la base de datos ya existe, por ejemplo, saltarse la creación.
            $tenant1 = Tenant::where('id', 'foo')->first();
        }

        try {
            $tenant2 = Tenant::create(['id' => 'bar']);
            $tenant2->domains()->create(['domain' => 'bar.localhost']);
        } catch (TenantDatabaseAlreadyExistsException $e) {
            // Manejar la excepción si la base de datos ya existe
            $tenant2 = Tenant::where('id', 'bar')->first();
        }
    }
}
