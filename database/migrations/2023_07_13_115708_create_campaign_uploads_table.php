<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreateCampaignUploadsTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
            $table->unsignedBigInteger('upload_type_id');
            $table->foreign('upload_type_id')->references('id')->on('upload_types');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('file_name')->nullable();
            $table->string('path')->nullable();
            $table->string('link')->nullable();
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
        Schema::dropIfExists('campaign_uploads');
    }
}
