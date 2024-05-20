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

    /*----------------------------------------------------------------
                Définition des rôles
     -----------------------------------------------------------------*/
        DB::table("roles")->insert([
            [
                "name"=>"Admin",
                "guard_name"=>"web"

            ],
            [
                "name"=>"DG",
                "guard_name"=>"web"

            ],
            [
                "name"=>"SGADMN",
                "guard_name"=>"web"

            ],
            [
                "name"=>"SGACAD",
                "guard_name"=>"web"

            ],
            [
                "name"=>"COMGER",
                "guard_name"=>"web"

            ],
            [
                "name"=>"ADMIN_BUDGET",
                "guard_name"=>"web"

            ],
            [
                "name"=>"SACAD",
                "guard_name"=>"web"

            ],

        ]);
    /*----------------------------------------------------------------
                Définition des permissions
     -----------------------------------------------------------------*/
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
                "name"=>"Update Annees",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Sections",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Departements",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Classes",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Etudiants",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Frais",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Inscriptions",
                "guard_name"=>"web"
            ],
            [
                "name"=>"Update Paiements",
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
                "guard_name"=>"web"
            ],
            [

                "name"=>"View Etats",
                "guard_name"=>"web"
            ],
            [

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
                "name"=>"ViewAny Etats",
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
    /*----------------------------------------------------------------
                Création de l'Administrateur
     -----------------------------------------------------------------*/
        $user=User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        $user->assignRole("Admin");

    /*----------------------------------------------------------------
                Attribution des permissions à des rôles
    -----------------------------------------------------------------*/

        $DG=Role::findByName("DG");
        $DG->givePermissionTo([
                "ViewAny Etats",
                "ViewAny Annees",
                "ViewAny Sections",
                "ViewAny Departements",
                "ViewAny Classes",
                "ViewAny Etudiants",
                "ViewAny Frais",
                "ViewAny Inscriptions",
                "ViewAny Paiements",
        ]);
        $COMGER=Role::findByName("COMGER");
        $COMGER->givePermissionTo([
                "ViewAny Etats",
                "ViewAny Annees",
                "ViewAny Sections",
                "ViewAny Departements",
                "ViewAny Classes",
                "ViewAny Etudiants",
                "ViewAny Frais",
                "ViewAny Inscriptions",
                "ViewAny Paiements",
        ]);
     }


}
