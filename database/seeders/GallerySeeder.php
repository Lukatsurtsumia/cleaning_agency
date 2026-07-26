<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['title' => 'Résidence Le Falicon, parties communes', 'category' => 'immeubles', 'description' => 'Entretien hebdomadaire des halls, escaliers et ascenseurs sur trois bâtiments.'],
            ['title' => 'Hôtel Promenade, recouches', 'category' => 'hotelier', 'description' => 'Recouches et mises à blanc quotidiennes, 48 chambres en haute saison.'],
            ['title' => 'Bureaux Arenas, entretien du soir', 'category' => 'bureaux', 'description' => 'Passage quotidien après 19 h pour un plateau de 14 postes.'],
            ['title' => 'Désinfection renforcée, cabinet médical', 'category' => 'specifiques', 'description' => 'Désinfection complète et traitement des surfaces de contact, protocole renforcé.'],
            ['title' => 'Remise en état après travaux, Cimiez', 'category' => 'specifiques', 'description' => 'Dépoussiérage complet et remise en état d\'un appartement après rénovation.'],
            ['title' => 'Résidence étudiante, rotation', 'category' => 'immeubles', 'description' => 'Remise en état de douze studios entre deux périodes de location.'],
        ];

        foreach ($projects as $project) {
            Gallery::firstOrCreate(['title' => $project['title']], $project);
        }
    }
}
