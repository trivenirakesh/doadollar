<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreateDonationsTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->unsignedBigInteger('payment_type_id');
            $table->foreign('payment_type_id')->references('id')->on('payment_gateway_settings');
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->foreign('entity_id')->references('id')->on('entitymst');
            $table->decimal('donation_amount',8,2);
            $table->decimal('tip',8,2)->nullable();
            $table->decimal('longitude',8,6)->nullable();
            $table->decimal('latitude',8,6)->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->nullable();
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
        Schema::dropIfExists('donations');
    }
}
