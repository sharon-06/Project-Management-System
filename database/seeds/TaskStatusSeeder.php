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
        $taskStatus->class = "text-danger";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "Pending";
        $taskStatus->class = "text-warning";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "In Progress";
        $taskStatus->class = "text-info";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();

        $taskStatus = new taskStatus();
        $taskStatus->name = "Completed";
        $taskStatus->class = "text-success";
        $taskStatus->created_by = 1;
        $taskStatus->updated_by = 1;
        $taskStatus->save();
    }
}
