<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\CommonTrait;

class CreateUsersTable extends Migration
{
    use CommonTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entitymst', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('email',200);
            $table->string('mobile',15)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default('1')->comment("0 - Deactive, 1 - Active");
            $table->tinyInteger('entity_type')->default('3')->comment("0 - Super Admin, 1 - Manager, 2 - User");
            $table->integer('role_id')->nullable();
            $table->integer('country')->nullable();
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();
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
        Schema::dropIfExists('users');
    }
}
