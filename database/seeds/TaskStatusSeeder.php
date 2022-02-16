<?php

namespace Database\Seeders;

use App\taskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskStatus = new taskStatus();
        $taskStatus->name = "Unaccepted";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "Pending";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "In Progress";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "Completed";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();
    }
}
