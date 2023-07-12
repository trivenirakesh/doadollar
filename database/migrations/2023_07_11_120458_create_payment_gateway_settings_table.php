<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreatePaymentGatewaySettingsTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('api_key');
            $table->text('secret_key');
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->tinyInteger('status')->default('1')->comment("0 - Deactive, 1 - Active")->nullable();
            $this->timestampColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway_settings');
    }
}
