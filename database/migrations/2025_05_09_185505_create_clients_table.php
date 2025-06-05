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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->boolean('client_actif')->default(false)->nullable();
            $table->string('client_name');
            $table->boolean('has_tin'     )->default(false)->nullable();
            $table->string('tin')->nullable();
            $table->string('autre_id')->nullable();
            $table->integer('points')->nullable();
            $table->decimal('solde', 10, 2)->nullable();
    
            $table->string('contact_personne')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('telephone')->nullable();
            $table->string('fax_code')->nullable();
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
    
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('google')->nullable();
    
            $table->string('adresse')->nullable();
            $table->string('complement')->nullable();
            $table->string('adresse_sup')->nullable();
            $table->string('immeuble')->nullable();
            $table->string('region')->nullable();
            $table->string('district')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->nullable();
    
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
