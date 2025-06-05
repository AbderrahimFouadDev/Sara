<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('devis', function (Blueprint $table) {
        $table->string('numero')->unique();                 // e.g., DVS-0001
        $table->decimal('montant', 10, 2)->default(0);      // e.g., 1250.00
        $table->string('statut')->default('en attente');    // e.g., en attente, validé, annulé
    });
}



    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('devis', function (Blueprint $table) {
        $table->dropColumn(['numero', 'montant', 'statut']);
    });
}

};
