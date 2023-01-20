<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * getLogs
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getLogs(Request $request): JsonResponse
    {
        $logQuery = Log::query();

        $logsCount = $logQuery->when($request->has('serviceNames'), function ($query) use ($request) {
            $names = $request->has('serviceNames');
            $names = is_array($names) ? $names : [$names];

            return $query->whereIn('name', $names);
        })->when($request->has('statusCode'), function ($query) use ($request) {
            return $query->where('status', $request->statusCode);
        })->when($request->has('startDate') and $request->has('endDate'), function ($query) use ($request) {
            return $query->where('created_at', '>', $request->startDate)->where(
                'created_at',
                '<',
                $request->endDate
            );
        })->count();

        return response()->json([
            'count' => $logsCount
        ]);
    }
}
