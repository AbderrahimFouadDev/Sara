<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('nom');
            $table->boolean('is_service')->default(false);
            $table->text('description')->nullable();
            
            // Foreign keys
           $table->foreignId('categorie_id')
      ->nullable()
      ->references('id')->on('categories')
      ->nullOnDelete(); // equivalent to onDelete('set null')

                  
           $table->foreignId('fournisseur_id')
      ->nullable()
      ->references('id')->on('fournisseurs')
      ->nullOnDelete(); // equivalent to onDelete('set null')

            
            // Price information
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_vente', 10, 2)->default(0);
            
            // Stock management
            $table->integer('quantite')->default(0);
            $table->integer('quantite_min')->default(0); // For stock alerts
            $table->string('unite')->nullable(); // Unit of measurement (e.g., kg, pcs, etc.)
            
            // Product identification
            $table->string('code_barre')->nullable()->unique();
            $table->string('reference')->nullable()->unique();
            
            // Timestamps and soft delete
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index('nom');
            $table->index('is_service');
            $table->index(['categorie_id', 'fournisseur_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}; 