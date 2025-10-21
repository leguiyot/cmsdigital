<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@cmsdigital.com'],
            [
                'name' => 'Administrador del Sistema',
                'email' => 'admin@cmsdigital.com',
                'password' => Hash::make('admin123'),
                'bio' => 'Administrador principal del sistema CMS Digital',
                'position' => 'Director General',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        // Crear usuario editor
        $editor = User::firstOrCreate(
            ['email' => 'editor@cmsdigital.com'],
            [
                'name' => 'Editor Principal',
                'email' => 'editor@cmsdigital.com',
                'password' => Hash::make('editor123'),
                'bio' => 'Editor principal responsable de la revisión y publicación de contenidos',
                'position' => 'Editor Jefe',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $editor->assignRole('editor');

        // Crear usuario redactor
        $writer = User::firstOrCreate(
            ['email' => 'redactor@cmsdigital.com'],
            [
                'name' => 'Redactor Principal',
                'email' => 'redactor@cmsdigital.com',
                'password' => Hash::make('redactor123'),
                'bio' => 'Redactor especializado en política y economía',
                'position' => 'Redactor Senior',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $writer->assignRole('writer');

        // Crear usuario colaborador
        $collaborator = User::firstOrCreate(
            ['email' => 'colaborador@cmsdigital.com'],
            [
                'name' => 'Colaborador Externo',
                'email' => 'colaborador@cmsdigital.com',
                'password' => Hash::make('colaborador123'),
                'bio' => 'Colaborador especializado en cultura y sociedad',
                'position' => 'Colaborador',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $collaborator->assignRole('collaborator');

        $this->command->info('Usuarios creados exitosamente:');
        $this->command->info('Admin: admin@cmsdigital.com / admin123');
        $this->command->info('Editor: editor@cmsdigital.com / editor123');
        $this->command->info('Redactor: redactor@cmsdigital.com / redactor123');
        $this->command->info('Colaborador: colaborador@cmsdigital.com / colaborador123');
    }
}
