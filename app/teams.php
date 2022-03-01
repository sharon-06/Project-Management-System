<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class teams extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'team_leader', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];


    /**
     * Get the team_leader of this wikiCategories.
     */
    public function leader(){
        return $this->belongsTo(User::class,'team_leader');
    }

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
        return $this->belongsToMany(User::class, 'user_has_teams', 'team_id','user_id');
    }
}
