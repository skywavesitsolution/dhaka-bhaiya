<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('exp_name');
            $table->float('total_amount');
            $table->date('date');
            $table->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');

            $table->foreignId('category_id')
                ->constrained('expense_categories')
                ->onDelete('cascade');

            $table->foreignId('sub_category_id')
                ->constrained('expense_sub_categories')
                ->onDelete('cascade');

            $table->integer('user_id');
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
        Schema::dropIfExists('expenses');
    }
}
