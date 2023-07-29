<?php

namespace App\Helper;
if (!function_exists('printResult')) {
	function printResult($data ,string $message = null,int $status_code = 200) {
		return response()->json([
			'message' => $message,
			'data' => $data
								],$status_code);
	}
}