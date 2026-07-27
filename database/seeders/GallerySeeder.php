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
            ['title' => 'Hôtel, Promenade des Anglais', 'category' => 'hotelier', 'description' => 'Remise à blanc des chambres et entretien quotidien des espaces communs.'],
            ['title' => 'Appartement, Cimiez', 'category' => 'immeubles', 'description' => 'Nettoyage complet d\'un appartement entre deux locations.'],
            ['title' => 'Réception privée, Cap de Nice', 'category' => 'specifiques', 'description' => 'Préparation avant réception et remise en état complète après l\'événement.'],
            ['title' => 'Villa, Saint-Jean-Cap-Ferrat', 'category' => 'bureaux', 'description' => 'Grand nettoyage de fin de location saisonnière, intérieur et extérieur.'],
            ['title' => 'Résidence, Carré d\'Or', 'category' => 'immeubles', 'description' => 'Entretien hebdomadaire des halls, escaliers et ascenseurs.'],
        ];

        foreach ($projects as $project) {
            Gallery::firstOrCreate(['title' => $project['title']], $project);
        }
    }
}
