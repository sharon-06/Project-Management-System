<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Carbon\Carbon;

class tasks extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];


    /**
     * Get the creator of this wikiCategories.
     */
    public function creator(){
        return $this->belongsTo(User::class,'created_by');
    }

    /**
     * Get the last editor of this wikiCategories.
     */
    public function editor(){
        return $this->belongsTo(User::class,'updated_by');
    }

    /**
     * The users that belong to the branch.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_has_tasks', 'task_id','user_id');
    }

    

    /**
     * The users that belong to the branch.
     */
    public function taskStatus()
    {
        return $this->belongsToMany(taskStatus::class, 'tasks_has_taskstatuses', 'task_id','taskstatuses_id')->withTimestamps()->withPivot('id','created_by', 'updated_by', 'created_at');
    }

    /**
     * The users that belong to the branch.
     */
    public function Tasks_has_taskstatus()
    {
        return $this->hasMany(Tasks_has_taskstatus::class,'task_id','id');
    }

    

}
