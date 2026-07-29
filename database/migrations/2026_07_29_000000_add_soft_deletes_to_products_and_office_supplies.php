<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesToProductsAndOfficeSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This project's migration ledger has a required, non-incrementing ID.
        // Repair it so Laravel can record this and future migrations normally.
        if (Schema::hasColumn('migrations', 'id')) {
            $columns = DB::select("SHOW COLUMNS FROM migrations WHERE Field = 'id'");
            $idColumn = reset($columns);

            if ($idColumn && strpos($idColumn->Extra, 'auto_increment') === false) {
                $primaryKey = $idColumn->Key === 'PRI' ? '' : ', ADD PRIMARY KEY (`id`)';
                DB::statement(
                    'ALTER TABLE `migrations` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT'.$primaryKey
                );
            }
        }

        if (! Schema::hasColumn('products', 'deleted_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (! Schema::hasColumn('office_supplies', 'deleted_at')) {
            Schema::table('office_supplies', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('products', 'deleted_at')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('office_supplies', 'deleted_at')) {
            Schema::table('office_supplies', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
