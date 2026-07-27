<?php

return [

    'brand' => [
        'tagline' => 'Nettoyage professionnel',
        'tagline_full' => 'Service de nettoyage professionnel et flexible',
        'manager_role' => 'Gérante',
    ],

    'nav' => [
        'home' => 'Accueil',
        'services' => 'Services',
        'pricing' => 'Tarifs',
        'gallery' => 'Réalisations',
        'about' => 'À propos',
        'contact' => 'Contact',
        'book' => 'Réserver',
        'menu' => 'Menu',
        'close' => 'Fermer',
        'lang_label' => 'Langue',
    ],

    'hero' => [
        'eyebrow' => 'Nice et Côte d\'Azur',
        'title_line1' => 'Des espaces impeccables,',
        'title_line2' => 'une image irréprochable.',
        'lead' => 'Azur Clean Tinati assure le nettoyage professionnel des hôtels, résidences et bureaux sur toute la Côte d\'Azur. Rigoureux, flexible, écologique.',
        'cta_book' => 'Réserver une intervention',
        'cta_quote' => 'Voir les tarifs',
        'scroll' => 'Découvrir',
        'prev' => 'Diapositive précédente',
        'next' => 'Diapositive suivante',
        'goto' => 'Aller à la diapositive :n',
    ],

    'services' => [
        'eyebrow' => 'Nos prestations',
        'title' => 'Ce que nous nettoyons',
        'lead' => 'Quatre familles de prestations, adaptables en entretien régulier ou en intervention ponctuelle.',
        'cta' => 'Demander un devis',
        'items' => [
            [
                'key' => 'hotelier',
                'name' => 'Nettoyage hôtelier',
                'summary' => 'Chambres, couloirs, halls et espaces communs.',
                'points' => ['Chambres et salles de bain', 'Couloirs et halls', 'Linge et serviettes', 'Espaces communs'],
                'icon' => 'bed',
            ],
            [
                'key' => 'immeubles',
                'name' => 'Immeubles et résidences',
                'summary' => 'Parties communes, ascenseurs, escaliers, halls d\'accueil.',
                'points' => ['Halls d\'entrée', 'Escaliers et paliers', 'Ascenseurs', 'Local poubelles'],
                'icon' => 'building',
            ],
            [
                'key' => 'bureaux',
                'name' => 'Bureaux et locaux professionnels',
                'summary' => 'Entretien régulier ou ponctuel, en horaires décalés.',
                'points' => ['Bureaux et postes de travail', 'Sanitaires et cuisines', 'Salles de réunion', 'Sols et surfaces'],
                'icon' => 'briefcase',
            ],
            [
                'key' => 'specifiques',
                'name' => 'Services spécifiques',
                'summary' => 'Vitres, remise en état après travaux, désinfection, urgences.',
                'points' => ['Nettoyage des vitres', 'Après travaux', 'Désinfection', 'Intervention urgente'],
                'icon' => 'spray',
            ],
        ],
    ],

    'pricing' => [
        'eyebrow' => 'Tarifs',
        'title' => 'Simple et transparent',
        'lead' => 'Un tarif de départ clair, puis un devis gratuit ajusté à votre site sous 24 h.',
        'from' => 'À partir de',
        'per_hour' => '/ heure',
        'on_quote' => 'Sur devis',
        'popular' => 'Le plus demandé',
        'cta' => 'Demander un devis',
        'note' => 'Tarifs indicatifs hors taxes. Le devis final dépend de la surface, de la fréquence et des contraintes du site.',
        'tiers' => [
            'residences' => [
                'name' => 'Résidences et immeubles',
                'for' => 'Syndics, bailleurs, copropriétés',
                'includes' => ['Parties communes', 'Passage régulier', 'Sorties de conteneurs', 'Produits fournis'],
            ],
            'bureaux' => [
                'name' => 'Bureaux et commerces',
                'for' => 'TPE, PME, boutiques',
                'includes' => ['Entretien quotidien ou hebdomadaire', 'Horaires décalés possibles', 'Sanitaires et espaces communs', 'Produits fournis'],
            ],
            'hotellerie' => [
                'name' => 'Hôtellerie et contrats',
                'for' => 'Hôtels, résidences hôtelières',
                'includes' => ['Recouches et mises à blanc', 'Équipe dédiée', 'Contrôle qualité', 'Engagement contractuel'],
            ],
        ],
    ],

    'gallery' => [
        'eyebrow' => 'Réalisations',
        'title' => 'Projets récents',
        'lead' => 'Quelques interventions récentes, avant et après.',
        'all' => 'Tout voir',
        'view' => 'Voir le chantier',
        'empty' => 'Les premières réalisations arrivent bientôt.',
        'back' => 'Retour à l\'accueil',
    ],

    'about' => [
        'eyebrow' => 'À propos',
        'title' => 'Tina Babayan, fondatrice',
        'body' => 'Huit ans comme gouvernante générale en hôtellerie, notamment chez Adagio Nice Centre et Mama Shelter Nice. J\'ai créé Azur Clean Tinati pour apporter ce même niveau d\'exigence aux résidences, bureaux et hôtels de la Côte d\'Azur.',
        'quote' => 'La propreté ne va jamais sans la sécurité, ni sans la confiance.',
        'stats' => [
            ['value' => '8 ans', 'label' => 'en gouvernance hôtelière'],
            ['value' => '06', 'label' => 'Nice et Côte d\'Azur'],
            ['value' => '24 h', 'label' => 'délai de réponse'],
        ],
        'eco_title' => 'Engagement environnemental',
        'eco_body' => 'Produits naturels ou prêts à l\'emploi pour éviter les erreurs de dosage, emballages réutilisables, tri sélectif et gestion maîtrisée de l\'eau et de l\'électricité.',
    ],

    // The public booking section (form, then a mailto: CTA) has since been
    // removed entirely — "Réserver"/"Book" now just scrolls to #contact.
    // This key survives only because App\Mail\BookingReceived/BookingConfirmation
    // still render existing Booking records (admin dashboard, quote PDF, .ics
    // feed) and read property_types.
    'booking' => [
        'property_types' => [
            'apartment' => 'Appartement / résidence',
            'house' => 'Villa / maison',
            'office' => 'Bureaux / local professionnel',
        ],
    ],

    'contact' => [
        'eyebrow' => 'Contact',
        'title' => 'Parlons de votre site',
        'lead' => 'Une question, un devis, une intervention urgente ? Nous répondons sous 24 h.',
        'manager' => 'Direction',
        'assistant' => 'Assistante de direction',
        'address_label' => 'Adresse',
        'directions' => 'Itinéraire',
        'area_body' => 'Nice et l\'ensemble de la Côte d\'Azur.',
        'hours_label' => 'Horaires',
        'hours_value' => 'Lun - Ven, 8h - 18h · Sam, 8h - 13h',
        'direct_title' => 'Le plus simple : appelez-nous',
        'direct_body' => 'Un appel suffit pour organiser une visite ou obtenir un devis. Vous préférez choisir votre date vous-même ? Réservez en ligne en une minute.',
        'or' => 'ou',
        'form_title' => 'Écrivez-nous',
        'form_lead' => 'Décrivez votre besoin, nous revenons vers vous sous 24 h.',
        'reach_title' => 'Nous joindre',
        'call_cta' => 'Appeler maintenant',
        'success' => 'Merci, votre message est bien parti. Nous vous répondons sous 24 h.',
        'fields' => [
            'name' => 'Nom complet',
            'email' => 'Email',
            'phone' => 'Téléphone',
            'subject' => 'Sujet',
            'message' => 'Votre message',
            'message_placeholder' => 'Type de site, surface, fréquence souhaitée, vos questions...',
        ],
        'submit' => 'Envoyer le message',
    ],

    'location' => [
        'eyebrow' => 'Localisation',
        'title' => 'Où nous trouver',
        'lead' => 'Basés à Nice, nous intervenons sur toute la Côte d\'Azur.',
    ],

    'footer' => [
        'rights' => 'Tous droits réservés.',
        'legal' => 'Mentions légales',
        'nav_title' => 'Navigation',
        'contact_title' => 'Contact',
        'credit' => 'Conçu par Luka Tsurtsumia',
    ],

    'common' => [
        'call' => 'Appeler',
        'email' => 'Envoyer un email',
        'required' => 'obligatoire',
        'optional' => 'optionnel',
    ],

];
