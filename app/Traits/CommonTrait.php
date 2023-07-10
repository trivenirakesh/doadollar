<?php

namespace App\Traits;

trait CommonTrait{

    public function successResponse($data, $message = null, $code = 200)
	{
        
		return response()->json([
			'status'=> 'success', 
			'message' => $message, 
			'data' => $data
		], $code);
	}

    public function errorResponse($message = null, $code)
	{
		return response()->json([
			'status'=>'error',
			'message' => $message,
			'data' => []
		], $code);
	}

	public function timestampColumns($table){
        $table->integer('created_by')->nullable();
        $table->timestamp('created_at')->nullable();
        $table->string('created_ip')->nullable();
        $table->integer('updated_by')->nullable();
        $table->timestamp('updated_at')->nullable();
        $table->string('updated_ip')->nullable();
		$table->integer('deleted_by')->nullable();
		$table->softDeletes();
		$table->string('deleted_ip')->nullable();
        return $table;
    }

}