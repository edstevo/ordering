<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table) {
            $table->increments('id');
            $table->string('currency')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('email')->nullable();
            $table->string('tel')->nullable();
            $table->boolean('invoiced')->nullable();
            $table->timestamp('invoiced_at')->nullable();
            $table->string('charge_id')->nullable();
            $table->timestamp('charged_at')->nullable();
            $table->boolean('paid')->default(false);
            $table->boolean('dispatched')->nullable();
            $table->timestamp('dispatched_at')->nullable();
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
        Schema::drop('orders');
    }
}
