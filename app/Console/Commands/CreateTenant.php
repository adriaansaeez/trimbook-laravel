<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Str;

class CreateTenant extends Command
{
    protected $signature = 'tenants:create {domain}';
    protected $description = 'Crea un tenant con dominio y ejecuta sus migraciones';

    public function handle()
    {
        $domain = $this->argument('domain');

        // Ej: domain = syndicate.trimbook.tech → ID = syndicate
        $id = Str::before($domain, '.');

        // Nombre de la BD esperada: trimbook_tenant{id}
        $databaseName = 'trimbook_tenant' . $id;

        // Crear el tenant con la BD personalizada
        if (Tenant::find($id)) {
            $this->warn("El tenant '$id' ya existe. Saliendo.");
            return;
        }
        
        $tenant = new Tenant();
        $tenant->id = $id;
        $tenant->tenancy_db_name = $databaseName;
        $tenant->saveQuietly(); // Evita que dispare eventos como CreateDatabase
        

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        $this->info("Tenant '{$id}' creado con dominio '{$domain}' y base de datos '{$databaseName}'");

        // Ejecutar migraciones tenant
        $this->call('tenants:migrate', [
            '--tenants' => [$id],
        ]);

        $this->info("Migraciones ejecutadas para tenant '{$id}'");
    }
}
