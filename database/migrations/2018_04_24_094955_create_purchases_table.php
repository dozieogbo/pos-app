<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->uuid('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->boolean('is_active')->default(false);
            $table->decimal('amount_paid', 15, 2);
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
        Schema::dropIfExists('purchases');
    }
}
