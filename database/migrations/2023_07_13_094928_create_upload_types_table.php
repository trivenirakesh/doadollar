<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreateUploadTypesTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('type')->comment("0 - Upload, 1 - Links");
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
        Schema::dropIfExists('upload_types');
    }
}
