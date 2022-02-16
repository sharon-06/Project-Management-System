<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class taskStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'created_by', 'updated_by', 'created_at', 'updated_at'
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
    public function task()
    {
        return $this->belongsToMany(Task::class, 'tasks_has_taskstatuses', 'taskstatuses_id','task_id')->withPivot('created_by', 'updated_by');
    }
}
