<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreateCampaignsTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_category_id');
            $table->foreign('campaign_category_id')->references('id')->on('campaign_categories');
            $table->string('name',200);
            $table->text('description')->nullable();
            $table->string('unique_code',255)->nullable();
            $table->timestamp('start_datetime')->nullable();
            $table->timestamp('end_datetime')->nullable();
            $table->decimal('donation_target',8,2)->nullable();
            $table->tinyInteger('status')->default('1')->comment("0 - Deactive, 1 - Active")->nullable();
            $table->tinyInteger('campaign_status')->default('0')->comment("0 - Pending, 1 - OnGoing, 2 - Completed, 3 - Cancelled, 4 - Rejected, 5 - Approved")->nullable();
            $table->string('qr_image',255)->nullable();
            $table->string('qr_path',255)->nullable();
            $table->string('cover_image',255)->nullable();
            $table->string('cover_image_path',255)->nullable();
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
        Schema::dropIfExists('campaigns');
    }
}
