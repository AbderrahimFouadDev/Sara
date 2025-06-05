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
        Schema::table('factures', function (Blueprint $table) {
            $table->date('date_echeance')->nullable()->after('date_facture');
            $table->decimal('montant_ht', 10, 2)->default(0)->after('montant');
            $table->decimal('montant_tva', 10, 2)->default(0)->after('montant_ht');
            $table->decimal('montant_ttc', 10, 2)->default(0)->after('montant_tva');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['date_echeance', 'montant_ht', 'montant_tva', 'montant_ttc']);
        });
    }
}; 