<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('devis_product', function (Blueprint $table) {
            if (!Schema::hasColumn('devis_product', 'price')) {
                $table->decimal('price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('devis_product', 'tva_rate')) {
                $table->decimal('tva_rate', 5, 2)->default(20.00);
            }
            if (!Schema::hasColumn('devis_product', 'tva_amount')) {
                $table->decimal('tva_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('devis_product', 'total_ttc')) {
                $table->decimal('total_ttc', 10, 2)->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('devis_product', function (Blueprint $table) {
            $table->dropColumn(['price', 'tva_rate', 'tva_amount', 'total_ttc']);
        });
    }
}; 