<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HealthCheckService;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function index(HealthCheckService $service)
    {
        $history = $service->getHistory();
        return view('admin.health.index', compact('history'));
    }

    public function run(Request $request, HealthCheckService $service)
    {
        $keep = $request->boolean('keep');
        $report = $service->run($keep);
        $history = $service->getHistory();
        return view('admin.health.index', compact('report','history'));
    }
}
