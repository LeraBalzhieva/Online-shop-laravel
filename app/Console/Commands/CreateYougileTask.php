<?php

namespace App\Console\Commands;

use App\Jobs\CreateTaskYougile;
use App\Models\Order;

class CreateYougileTask
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected string $signature = 'app:create-yougile-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected string $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::whereNull('yougile_task_id')->get();
        foreach ($orders as $order) {
            CreateTaskYougile::dispatch($order);
        }
    }

}
