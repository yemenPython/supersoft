<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('bank_data_id');
            $table->unsignedBigInteger('branch_product_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('main_type_bank_account_id');
            $table->unsignedBigInteger('sub_type_bank_account_id')->nullable();
            $table->unsignedBigInteger('bank_account_child_id')->nullable();

            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('iban')->nullable();
            $table->string('customer_number')->nullable();
            $table->string('granted_limit')->nullable();
            $table->string('deposit_number')->nullable();
            $table->string('deposit_term')->nullable();
            $table->string('periodicity_return_disbursement')->nullable();

            $table->date('account_open_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('Last_renewal_date')->nullable();
            $table->date('deposit_opening_date')->nullable();
            $table->date('deposit_expiry_date')->nullable();

            $table->decimal('deposit_amount')->nullable();
            $table->decimal('interest_rate')->nullable();

            $table->enum('type', ['deposit_for', 'savings_certificate'])->nullable();
            $table->enum('yield_rate_type', ['fixed', 'not_fixed'])->nullable();

            $table->boolean('auto_renewal')->default(0);
            $table->boolean('with_returning')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('check_books')->default(0);
            $table->boolean('overdraft')->default(0);
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
        Schema::dropIfExists('bank_accounts');
    }
}
