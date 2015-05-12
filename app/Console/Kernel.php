<?php namespace Keep\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
        'Keep\Console\Commands\FailedTasksCommand'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
        $schedule->exec('composer self-update')
            ->weekly()
            ->withoutOverlapping()
            ->environments('production');

        $schedule->command('keep:failed-tasks')
            ->everyFiveMinutes()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();
	}

}
