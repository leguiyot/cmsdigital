<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para artículos
        $articlePermissions = [
            'view articles',
            'create articles',
            'edit articles',
            'delete articles',
            'publish articles',
            'feature articles',
        ];

        // Crear permisos para secciones
        $sectionPermissions = [
            'view sections',
            'create sections',
            'edit sections',
            'delete sections',
        ];

        // Crear permisos para comentarios
        $commentPermissions = [
            'view comments',
            'moderate comments',
            'delete comments',
        ];

        // Crear permisos para usuarios
        $userPermissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
        ];

        // Crear permisos para medios
        $mediaPermissions = [
            'view media',
            'upload media',
            'delete media',
        ];

        // Crear permisos para campañas publicitarias
        $adPermissions = [
            'view ads',
            'create ads',
            'edit ads',
            'delete ads',
        ];

        // Crear permisos para bloques destacados
        $featuredPermissions = [
            'view featured',
            'create featured',
            'edit featured',
            'delete featured',
        ];

        // Crear todos los permisos
        $allPermissions = array_merge(
            $articlePermissions,
            $sectionPermissions,
            $commentPermissions,
            $userPermissions,
            $mediaPermissions,
            $adPermissions,
            $featuredPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'editor']);
        $writerRole = Role::firstOrCreate(['name' => 'writer']);
        $collaboratorRole = Role::firstOrCreate(['name' => 'collaborator']);

        // Asignar permisos a roles
        
        // Administrador: todos los permisos
        $adminRole->givePermissionTo($allPermissions);

        // Editor: puede gestionar artículos, comentarios, medios y bloques destacados
        $editorRole->givePermissionTo([
            'view articles', 'create articles', 'edit articles', 'delete articles', 'publish articles', 'feature articles',
            'view sections',
            'view comments', 'moderate comments', 'delete comments',
            'view media', 'upload media', 'delete media',
            'view featured', 'create featured', 'edit featured', 'delete featured',
        ]);

        // Redactor: puede crear y editar artículos, subir medios
        $writerRole->givePermissionTo([
            'view articles', 'create articles', 'edit articles',
            'view sections',
            'view comments',
            'view media', 'upload media',
        ]);

        // Colaborador: puede crear artículos (requieren aprobación)
        $collaboratorRole->givePermissionTo([
            'view articles', 'create articles',
            'view sections',
            'view media', 'upload media',
        ]);
    }
}
