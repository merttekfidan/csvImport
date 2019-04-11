<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblProductDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblProductData', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->comment = 'Stores product data';

            $table->increments('intProductDataId');
            $table->string('strProductName',50)->unique();
            $table->string('strProductDesc',255);
            $table->string('strProductCode',10);
            $table->dateTime('dtmAdded')->nullable()->default(NULL);
            $table->dateTime('dtmDiscontinued')->nullable()->default(NULL);
            $table->timestamp('stmTimestamp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblProductData');
    }
}
