<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Permissões que controlam o acesso aos módulos operacionais.
     * A chave é usada no middleware/rotas, o valor é o rótulo exibido na tela.
     */
    public const MODULES = [
        'setores-hospitalares' => 'Setores Hospitalares',
        'especialidades-medicas' => 'Especialidades Médicas',
        'equipamentos' => 'Equipamentos',
        'unidades-assistenciais' => 'Unidades Assistenciais',
    ];

    public function run(): void
    {
        foreach (array_keys(self::MODULES) as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
