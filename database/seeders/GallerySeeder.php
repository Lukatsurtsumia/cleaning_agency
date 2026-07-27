<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['title' => 'Villa, Mont Boron', 'category' => 'bureaux', 'description' => 'Nettoyage complet d\'une villa avec terrasses et grandes baies vitrées.'],
            ['title' => 'Hôtel, Promenade des Anglais', 'category' => 'hotelier', 'description' => 'Nettoyage des chambres et entretien quotidien des espaces communs.'],
            ['title' => 'Appartement, Cimiez', 'category' => 'immeubles', 'description' => 'Nettoyage complet d\'un appartement entre deux locations.'],
            ['title' => 'Événement privé, Cap de Nice', 'category' => 'specifiques', 'description' => 'Nettoyage complet après un événement privé.'],
            ['title' => 'Villa, Saint-Jean-Cap-Ferrat', 'category' => 'bureaux', 'description' => 'Grand nettoyage de fin de location saisonnière, intérieur et extérieur.'],
            ['title' => 'Résidence, Carré d\'Or', 'category' => 'immeubles', 'description' => 'Entretien hebdomadaire des halls, escaliers et ascenseurs.'],
        ];

        // updateOrCreate (not firstOrCreate) so copy fixes to an existing
        // project's description actually apply when the seeder re-runs.
        foreach ($projects as $project) {
            Gallery::updateOrCreate(['title' => $project['title']], $project);
        }
    }
}
