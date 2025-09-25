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
    Schema::table('products', function (Blueprint $table) {
        $table->tinyInteger('status')->default(1)->after('price'); // 1 = active, 0 = inactive
    });
}


    /**
     * Reverse the migrations.
     */
   public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
