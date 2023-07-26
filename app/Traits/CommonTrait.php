<?php

namespace App\Traits;

trait CommonTrait
{

	public function successResponse($data, $message = null, $code = 200)
	{
		return response()->json([
			'status' => true,
			'message' => $message,
			'data' => $data
		], $code);
	}

	public function errorResponse($message = null, $code = 401)
	{
		return response()->json([
			'status' => false,
			'message' => $message,
			'data' => []
		], $code);
	}

	public function successResponseArr($message = 'success', $data = [])
	{
		return [
			'status' => true,
			'message' => $message,
			'data' => $data,
		];
	}

	public function errorResponseArr($message = 'Something went wrong!!', $errors = [])
	{
		return [
			'status' => false,
			'message' => $message,
			'data' => $errors
		];
	}


	public function timestampColumns($table)
	{
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

	public function actionHtml($baseurl, $id, $actionDelete = true)
	{
		if ($actionDelete) {
			$html = "<div class='actions-a' data-id='" . $id . "' data-url='" . $baseurl . "'>
			<a class='btn-circle btn-danger module_delete_record' title='Delete'><i class='fa fa-times'></i></a>
			</div>";
		} else {
			$html = "<div class='actions-a' data-id='" . $id . "' data-url='" . $baseurl . "'>
				<a class='btn-circle theme_primary_btn module_edit_record' title='Edit'><i class='fa fa-pen'></i></a>
				<a class='btn-circle theme_primary_btn module_view_record' title='View'><i class='fa fa-eye'></i></a>
				</div>";
		}
		return $html;
	}

	public function statusHtml($row)
	{
		$statusText = $row->status == 1 ? "Active" : "Inactive";
		$statusClass = $row->status == 1 ? "badge-primary" : " badge-danger";
		$status = "<span class='text-md badge badge-pill $statusClass'>$statusText</span>";
		return $status;
	}
}
