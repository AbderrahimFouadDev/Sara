<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['client_id']);
            
            // Then drop the columns
            $table->dropColumn(['client_id', 'client_name']);
            
            // Add new columns
            $table->string('fournisseur_name');
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            // Restore original columns
            $table->unsignedBigInteger('client_id')->nullable();
            $table->string('client_name');
            
            // Restore foreign key
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('set null');
            
            // Remove new columns
            $table->dropColumn('fournisseur_name');
        });
    }
}; 