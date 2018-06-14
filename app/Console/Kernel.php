<?php

namespace App\Console;

use App\Http\Controllers\Admin\AutotraderImporter;
use App\Models\Setting;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $setting = Setting::where('name','import_scheduler')->first();
        $importEnabled = Setting::where('name','enable_import_scheduler')->first();
        $hours = json_decode($setting->value);
        if($importEnabled->value == 1){
            try{
                foreach ($hours as $hour){
                    $schedule->call(function () {
                        $autotraderImporter = new AutotraderImporter();
                        $importer = $autotraderImporter->createByScheduler();
                        if(!$importer['error']){
                            $autotraderImporter->processByScheduler($importer['importerId']);
                        }
                    })->dailyAt($hour)
                    ->timezone('Europe/London');
                }
            }catch (\Exception $e){
                Log::info("Error In AutoImport Scheduler " . $e->getMessage());
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
