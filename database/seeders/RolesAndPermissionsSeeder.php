<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Database\Factories\UserFactory;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat Permissions
        // Dashboard
        Permission::firstOrCreate(['name' => 'view dashboard']);
        // Manajemen Pengguna
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'create user']);
        Permission::firstOrCreate(['name' => 'edit user']);
        Permission::firstOrCreate(['name' => 'delete user']);
        // Manajemen Siswa
        Permission::firstOrCreate(['name' => 'manage students']);
        // Manajemen Guru (Opsional jika ada modul terpisah untuk guru)
        Permission::firstOrCreate(['name' => 'manage teachers']);
        // Manajemen Kelas
        Permission::firstOrCreate(['name' => 'manage classes']);
        // Manajemen Mata Pelajaran
        Permission::firstOrCreate(['name' => 'manage subjects']);
        // Manajemen Penugasan Mengajar
        Permission::firstOrCreate(['name' => 'manage teaching assignments']);
        // Penilaian
        Permission::firstOrCreate(['name' => 'input grades']);
        Permission::firstOrCreate(['name' => 'view grades']);
        // Absensi
        Permission::firstOrCreate(['name' => 'record attendance']);
        Permission::firstOrCreate(['name' => 'view attendance']);
        // Komunikasi
        Permission::firstOrCreate(['name' => 'send announcements']);
        Permission::firstOrCreate(['name' => 'view announcements']);
        // Jadwal
        Permission::firstOrCreate(['name' => 'manage schedules']);
        Permission::firstOrCreate(['name' => 'view schedules']); // <-- TAMBAHKAN BARIS INI

        // 2. Buat Roles dan Beri Permissions (tetap sama, bisa diabaikan)
        $adminRole = Role::firstOrCreate(['name' => 'admin_sekolah']);
        $guruRole = Role::firstOrCreate(['name' => 'guru']);
        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $orangTuaRole = Role::firstOrCreate(['name' => 'orang_tua']);

        $adminRole->givePermissionTo(Permission::all());

        $guruRole->givePermissionTo([
            'view dashboard',
            'record attendance',
            'view attendance',
            'input grades',
            'view grades',
            'manage teaching assignments',
            'manage subjects',
            'send announcements',
            'manage schedules',
        ]);

        $siswaRole->givePermissionTo([
            'view dashboard',
            'view grades',
            'view attendance',
            'view announcements',
            'view schedules',
        ]);

        $orangTuaRole->givePermissionTo([
            'view dashboard',
            'view grades',
            'view attendance',
            'view announcements',
            'view schedules',
        ]);

        // 3. Buat User Admin Awal (tetap sama, bisa diabaikan)
        User::firstOrCreate(
            ['email' => 'admin@akademika.com'],
            ['name' => 'Admin Utama Akademika', 'password' => bcrypt('password'), 'email_verified_at' => now()]
        )->assignRole('admin_sekolah');

        // User Guru, Siswa, Orang Tua (tetap sama, bisa diabaikan)
        $fixedGuruCount = 3;
        User::firstOrCreate(['email' => 'guru@akademika.com'], ['name' => 'Guru Satu', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('guru');
        User::firstOrCreate(['email' => 'guru2@akademika.com'], ['name' => 'Guru Dua', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('guru');
        User::firstOrCreate(['email' => 'guru3@akademika.com'], ['name' => 'Guru Tiga', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('guru');
        // $totalGuruDesired = 10;
        // if ($totalGuruDesired > $fixedGuruCount) {
        //     User::factory()->count($totalGuruDesired - $fixedGuruCount)->create()->each(function (User $user) {
        //         $user->assignRole('guru');
        //     });
        // }

        $fixedSiswaCount = 3;
        User::firstOrCreate(['email' => 'siswa@akademika.com'], ['name' => 'Siswa Satu', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('siswa');
        User::firstOrCreate(['email' => 'siswa2@akademika.com'], ['name' => 'Siswa Dua', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('siswa');
        User::firstOrCreate(['email' => 'siswa3@akademika.com'], ['name' => 'Siswa Tiga', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('siswa');
        // $totalSiswaDesired = 50;
        // if ($totalSiswaDesired > $fixedSiswaCount) {
        //     User::factory()->count($totalSiswaDesired - $fixedSiswaCount)->create()->each(function (User $user) {
        //         $user->assignRole('siswa');
        //     });
        // }

        $fixedOrtuCount = 3;
        User::firstOrCreate(['email' => 'ortu@akademika.com'], ['name' => 'Orang Tua Satu', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('orang_tua');
        User::firstOrCreate(['email' => 'ortu2@akademika.com'], ['name' => 'Orang Tua Dua', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('orang_tua');
        User::firstOrCreate(['email' => 'ortu3@akademika.com'], ['name' => 'Orang Tua Tiga', 'password' => bcrypt('password'), 'email_verified_at' => now()])->assignRole('orang_tua');
        // $totalOrtuDesired = 20;
        // if ($totalOrtuDesired > $fixedOrtuCount) {
        //     User::factory()->count($totalOrtuDesired - $fixedOrtuCount)->create()->each(function (User $user) {
        //         $user->assignRole('orang_tua');
        //     });
        // }

        $this->command->info('Roles and permissions, and initial users seeded.');
    }
}
