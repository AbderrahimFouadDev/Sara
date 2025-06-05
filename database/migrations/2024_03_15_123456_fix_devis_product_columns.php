<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add columns using raw SQL to avoid issues with Schema builder
        $columns = [
            'price' => "ALTER TABLE devis_product ADD COLUMN IF NOT EXISTS price DECIMAL(10,2) NULL;",
            'tva_rate' => "ALTER TABLE devis_product ADD COLUMN IF NOT EXISTS tva_rate DECIMAL(5,2) DEFAULT 20.00;",
            'tva_amount' => "ALTER TABLE devis_product ADD COLUMN IF NOT EXISTS tva_amount DECIMAL(10,2) DEFAULT 0;",
            'total_ttc' => "ALTER TABLE devis_product ADD COLUMN IF NOT EXISTS total_ttc DECIMAL(10,2) DEFAULT 0;"
        ];

        foreach ($columns as $column => $sql) {
            try {
                DB::statement($sql);
            } catch (\Exception $e) {
                // Column might already exist, continue to next
                continue;
            }
        }
    }

    public function down()
    {
        $columns = ['price', 'tva_rate', 'tva_amount', 'total_ttc'];
        
        foreach ($columns as $column) {
            try {
                DB::statement("ALTER TABLE devis_product DROP COLUMN IF EXISTS {$column};");
            } catch (\Exception $e) {
                continue;
            }
        }
    }
}; 