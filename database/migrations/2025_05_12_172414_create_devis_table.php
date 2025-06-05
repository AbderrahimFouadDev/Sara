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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();

            // Foreign key to clients table
            $table->unsignedBigInteger('client_id');
            $table->string('client_name'); // Also saving the name directly

            // Devis details
            $table->date('date_devis');
            $table->string('groupe_devis')->default('estimate');

            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
