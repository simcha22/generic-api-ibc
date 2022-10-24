<?php

namespace App\Console\Commands;

use App\Models\Dashboard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create cache dashboard';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dashboards = Dashboard::query()->where('api_object',2)->get();

        foreach ($dashboards as $dashboard)
        {
            //$this->info($dashboard->object_header);
            if($dashboard->api_reload == 1){
                Cache::forget('dashboardConfig_' .$dashboard->id);
                Dashboard::query()->where('id', $dashboard->id)->update([
                    'api_reload' => 0,
                    'last_updated' => now()
                    ]);
            }

            if(Cache::has('dashboardConfig_' .$dashboard->id)){
                Log::channel('daily_cache')->info('dashboardConfig_' .$dashboard->id . ' Cache Already exists !');
                $this->info( 'Cache Already exists');
            }
            else{
                $result = DB::select($dashboard->object_query);
                Log::channel('daily_cache')->info('dashboardConfig_' .$dashboard->id . ' Cache Created !');
                Cache::put('dashboardConfig_' .$dashboard->id, $result, $dashboard->object_refresh_rate);
                $this->info( 'Cache Created');
            }
        }
        return 1;
    }
}
