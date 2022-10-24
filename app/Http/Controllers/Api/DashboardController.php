<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dashboard;
use App\Traits\ApiLogging;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Services\ExecDashboard;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    use ApiLogging, ApiResponser;

    public function index(Request $request, ExecDashboard $execDashboard){

        $startTime = microtime(true);
        $object = [];
        $dashboardsDate = [];
        $this->dashboardLogging($request->ip(),$request->ids);
        foreach ($request->ids as $id){

            $dashboardConfig = Dashboard::find($id);
            $dashboardsDate[] = $dashboardConfig;
            $object[] = $execDashboard->runQuery($dashboardConfig->object_query, $dashboardConfig->api_object, 'dashboardConfig_' .$dashboardConfig->id);
        }
        if(!empty($object)){
            return $this->successDashboard($request->ip(), $object, $dashboardsDate, number_format(microtime(true) - $startTime));
        }else{
            return $this->failedDashboard($request->ip(), number_format(microtime(true) - $startTime));
        }
    }
}
