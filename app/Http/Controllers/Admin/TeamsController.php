<?php

namespace App\Http\Controllers\Admin;

use App\teams;
use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreteamsRequest;
use App\Http\Requests\UpdateteamsRequest;

use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamsController extends Controller
{
    function __construct()
    {
        $this->middleware('can:create Team', ['only' => ['create', 'store']]);
        $this->middleware('can:edit Team', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete Team', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $team = teams::all();

        if(!auth()->user()->hasRole('superadmin')){
            $branch_id = auth()->user()->getBranchIdsAttribute();
            $users = User::select('id', 'name')->whereHas('branches', function($q) use ($branch_id) { $q->whereIn('branch_id', $branch_id); })->get();
        }else{
            $users = User::all('id', 'name');
        }

        return view('admin.team.create', compact("team","users"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreteamsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreteamsRequest $request)
    {
        try {

            $teams = new teams();
            $teams->title = $request->title;
            $teams->description = $request->description;
            $teams->team_leader = $request->team_leader;
            $teams->created_by = auth()->user()->id;
            $teams->updated_by = auth()->user()->id;
            $teams->save();

            $teams->users()->attach($request->user_id);
            $teams->backup()->attach($request->user_backup_id);

            //Session::flash('success', 'Team was created successfully.');
            //return redirect()->route('teams.index');

            return response()->json([
                'success' => 'Team was created successfully.' // for status 200
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            //Session::flash('failed', $exception->getMessage() . ' ' . $exception->getLine());
            /*return redirect()->back()->withInput($request->all());*/

            return response()->json([
                'error' => $exception->getMessage() . ' ' . $exception->getLine() // for status 200
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function show(teams $team)
    {
        return view('admin.team.show', compact("team"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function edit(teams $team)
    {
        if(!auth()->user()->hasRole('superadmin')){
            $branch_id = auth()->user()->getBranchIdsAttribute();
            $users = User::select('id', 'name')->whereHas('branches', function($q) use ($branch_id) { $q->whereIn('branch_id', $branch_id); })->get();
        }else{
            $users = User::all();
        }

        $teamUsers = $team->users->pluck('id')->toArray();
        $teamUsersBackup = $team->backup->pluck('id')->toArray();
        return view('admin.team.edit', compact('team','users', 'teamUsers', 'teamUsersBackup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateteamsRequest  $request
     * @param  \App\teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateteamsRequest $request, teams $team)
    {
        try {

            if (empty($team)) {
                //Session::flash('failed', 'Team Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Team update denied.' // for status 200
                ]);   
            }

            $team->title = $request->title;
            $team->description = $request->description;
            $team->team_leader = $request->team_leader;
            $team->updated_by = auth()->user()->id;
            $team->save();
            $team->users()->detach();

            $team->users()->syncWithoutDetaching($request->user_id);
            $team->backup()->syncWithoutDetaching($request->user_backup_id);
            //Session::flash('success', 'A Wiki Blog updated successfully.');
            //return redirect('admin/wikiBlog');

            return response()->json([
                'success' => 'Team update successfully.' // for status 200
            ]);

        } catch (\Exception $exception) {

            DB::rollBack();

            //Session::flash('failed', $exception->getMessage() . ' ' . $exception->getLine());
            /*return redirect()->back()->withInput($request->all());*/

            return response()->json([
                'error' => $exception->getMessage() . ' ' . $exception->getLine() // for status 200
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\teams  $teams
     * @return \Illuminate\Http\Response
     */
    public function destroy(teams $team)
    {
        // delete team
        $team->delete();

        //return redirect('admin/team')->with('delete', 'team deleted successfully.');
        return response()->json([
            'delete' => 'team deleted successfully.' // for status 200
        ]);
    }
}
