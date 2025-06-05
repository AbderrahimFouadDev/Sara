<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->unsignedBigInteger('id_fournisseur')->nullable();
            $table->foreign('id_fournisseur')->references('id')->on('fournisseurs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropForeign(['id_fournisseur']);
            $table->dropColumn('id_fournisseur');
        });
    }
}; 