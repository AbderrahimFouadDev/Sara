<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('facture_id')->constrained('factures')->onDelete('cascade');
            $table->date('date_remboursement');
            $table->decimal('montant', 10, 2);
            $table->text('motif');
            $table->enum('statut', ['en_cours', 'termine', 'refuse'])->default('en_cours');
            $table->timestamps();
            
            // Add index for better performance
            $table->index(['client_id', 'facture_id']);
            $table->index('date_remboursement');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remboursements');
    }
}; 