<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet');
            $table->string('cin')->unique();
            $table->string('cnss')->nullable(); // Numéro d'affiliation CNSS
            $table->string('poste');
            $table->string('departement');
            $table->enum('statut', ['actif', 'congé', 'quitté'])->default('actif');
            $table->date('date_embauche');
            $table->decimal('salaire_base', 10, 2);
            $table->string('photo')->nullable(); // Employee photo

            // Contrat
            $table->string('type_contrat')->nullable(); // CDD, CDI, Stage, etc.
            $table->date('date_debut_contrat')->nullable();
            $table->date('date_fin_contrat')->nullable();

            // Documents
            $table->string('document_cin')->nullable(); // fichier PDF/scan
            $table->string('document_cnss')->nullable();
            $table->string('document_contrat')->nullable();

            // Historique de paie (liée à une autre table)
            $table->json('dernier_bulletin')->nullable(); // JSON avec mois, net, brut, etc.

            // Absences et congés (résumé)
            $table->integer('jours_conges_restants')->default(0);
            $table->integer('jours_absences_non_justifiees')->default(0);

            // Historique
            $table->json('historique_promotions')->nullable(); // [{date, ancien_poste, nouveau_poste}]
            $table->json('historique_salaires')->nullable();   // [{date, montant}]
            $table->json('historique_contrats')->nullable();   // [{date_debut, date_fin, type}]

            // Génération de documents (références ou liens vers fichiers générés)
            $table->string('certificat_travail')->nullable();
            $table->string('solde_tout_compte')->nullable();

            // Statut DSN / CNSS export
            $table->boolean('exporte_dsn')->default(false);
            $table->boolean('exporte_cnss')->default(false);

            // Métadonnées pour tableau de bord RH
            $table->decimal('dernier_net_paye', 10, 2)->nullable();
            $table->string('mois_dernier_paye')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}
