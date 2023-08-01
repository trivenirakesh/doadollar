<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCampaignUploadsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign_uploads', function (Blueprint $table) {
            $table->dropForeign(['upload_type_id']);
            $table->dropColumn('upload_type_id');
        });
        Schema::table('campaign_uploads', function (Blueprint $table) {
            $table->enum('upload_type',['Upload', 'Links'])->after('campaign_id');;  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
