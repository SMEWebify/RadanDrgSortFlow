<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drgs', function (Blueprint $table) {
            $table->id();
            $table->string('drg_name');
            $table->string('file_path');
            $table->string('material');
            $table->decimal('thickness');
            $table->integer('sheet_qty');
            $table->integer('sheet_qty_done')->default(0);
            $table->decimal('unit_time');
            $table->integer('statu')->default(1);
            #1 = A planifier
            #2 = Planifier
            #3 = En cours
            #4 = A refaire
            #5 = Terminer
            #6 = Supprimer
            #7 = Stopper
			$table->text('comment', 65535)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drgs');
    }
}
