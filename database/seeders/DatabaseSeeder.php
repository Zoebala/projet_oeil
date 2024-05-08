<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        //définition de l'administrateur
        // $user=User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@gmail.com',
        // ]);

        // $role = Role::create(['name' => 'Admin']);
        // $user->assignRole($role);
        // $permission = Permission::create(['name' => 'edit articles']);


        //Définitions des permissions
        DB::table("permissions")->insert([
            [
                "name"=>"Create Annees",
                "guard_name"=>"web"

            ],
            [
                "name"=>"Create Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Classes",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Create Paiements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Annees",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Delete Paiements",
                "name"=>"View Annees",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Classes",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"View Paiements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Annees",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Classes",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"ViewAny Paiements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Annees",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Classes",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"DeleteAny Paiements",
                "guard_name"=>"web"
            ],

        ]);
    }
}
