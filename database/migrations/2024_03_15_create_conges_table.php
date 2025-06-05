<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salarie_id')->constrained('salaries')->onDelete('cascade');
            $table->enum('type', ['paid', 'sick', 'unpaid', 'other'])->default('paid');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->integer('duree');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('motif')->nullable();
            $table->text('commentaire')->nullable();
            $table->string('document_justificatif')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('conges');
    }
}; 