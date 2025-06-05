<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bon_livraison_product', function (Blueprint $table) {
            $table->decimal('price_ht', 10, 2)->after('quantity')->default(0);
            $table->decimal('remise_percent', 5, 2)->after('price_ht')->default(0);
            $table->decimal('remise_amount', 10, 2)->after('remise_percent')->default(0);
            $table->decimal('total_ht', 10, 2)->after('remise_amount')->default(0);
            $table->decimal('tva_rate', 5, 2)->after('total_ht')->default(20);
            $table->decimal('tva_amount', 10, 2)->after('tva_rate')->default(0);
            $table->decimal('total_ttc', 10, 2)->after('tva_amount')->default(0);
        });
    }

    public function down()
    {
        Schema::table('bon_livraison_product', function (Blueprint $table) {
            $table->dropColumn([
                'price_ht',
                'remise_percent',
                'remise_amount',
                'total_ht',
                'tva_rate',
                'tva_amount',
                'total_ttc'
            ]);
        });
    }
}; 