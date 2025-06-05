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
    Schema::create('bon_livraison', function (Blueprint $table) {
        $table->id();
$table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
        $table->string('client_name');
        $table->string('numero_bl')->unique();
        $table->date('date_livraison');
        $table->text('adresse_livraison');
        $table->string('mode_transport')->nullable();
        $table->string('reference_commande')->nullable();
        $table->string('etat')->default('En attente');
        $table->text('remarques')->nullable();
        $table->timestamps();

        // Foreign key constraint
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_livraison');
    }
};
