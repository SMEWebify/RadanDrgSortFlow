<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('drgs', function (Blueprint $table) {
            $table->unsignedBigInteger('machine_id')->nullable()->after('id');
            $table->foreign('machine_id')->references('id')->on('machines')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('drgs', function (Blueprint $table) {
            $table->dropForeign(['machine_id']);
            $table->dropColumn('machine_id');
        });
    }
};
