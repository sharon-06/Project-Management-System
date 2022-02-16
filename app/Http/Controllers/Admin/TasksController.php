<?php

namespace App\Http\Controllers\admin;

use App\tasks;
use App\User;
use App\taskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Traits\UploadTrait;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class TasksController extends Controller
{
    function __construct()
    {
        $this->middleware('can:create Task', ['only' => ['create', 'store']]);
        $this->middleware('can:edit Task', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete Task', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.task.index');
    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function datatables(Request $request)
    {

        if ($request->ajax() == true) {

            $data = tasks::select([
                                        'id',
                                        'title',
                                        'created_at',
                                        'updated_at',
                                    ])->with(['users','taskStatus' => function ($query) { 
                        $query->orderBy('id', 'desc')->with('editor')->first();
                }]);

            //dd($data[4]->taskStatus[0]->pivot_updated_by);

            
            return Datatables::eloquent($data)
                ->addColumn('users_avatars', function ($data) {
                    $users='<div class="avatars_overlapping">';
  
                    foreach ($data->users->reverse() as $key => $value) {
                       $users.='<span class="avatar_overlapping"><p tooltip="'.$value->name.'" flow="up"><img src="'.$value->getImageUrlAttribute($value->id).'" width="50" height="50" /></p></span>';
                    }

                    return $users.='</div>';
                })

                ->addColumn('taskAccepted', function ($data) {
                    if(isset($data->taskStatus[0])){
                        if($data->taskStatus[0]->pivot->updated_by==null || $data->taskStatus[0]->pivot->updated_by==''){
                            $taskAccepted = 'Not Accepted';
                        }else{

                            $taskAccepted = $data->taskStatus[0]->editor->name;
                            //$taskAccepted = '<img src="'.$data->taskStatus[0]->pivot->updated_by->getImageUrlAttribute($data->taskStatus[0]->pivot->updated_by).'" alt="user_id_'.$data->taskStatus[0]->pivot->updated_by.'" class="profile-user-img-small img-circle"> '. $data->taskStatus[0]->pivot->updated_by->name;
                        }
                    }else{
                        $taskAccepted = 'Not Accepted';
                    }
                
                    return $taskAccepted;
                    
                })

                ->addColumn('action', function ($data) {
                    
                    $html='';
                    if (auth()->user()->can('edit Task')){
                        $html.= '<a href="'.  route('admin.task.edit', ['task' => $data->id]) .'" class="btn btn-success btn-sm float-left mr-3"  id="popup-modal-button"><span tooltip="Edit" flow="left"><i class="fas fa-edit"></i></span></a>';
                    }

                    if (auth()->user()->can('delete Task')){
                        $html.= '<form method="post" class="float-left delete-form" action="'.  route('admin.task.destroy', ['task' => $data->id ]) .'"><input type="hidden" name="_token" value="'. Session::token() .'"><input type="hidden" name="_method" value="delete"><button type="submit" class="btn btn-danger btn-sm"><span tooltip="Delete" flow="up"><i class="fas fa-trash"></i></span></button></form>';
                    }

                    return $html; 
                })

                ->rawColumns(['users_avatars', 'action', 'taskAccepted'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasks = tasks::all();

        if(!auth()->user()->hasRole('superadmin')){
            $branch_id = auth()->user()->getBranchIdsAttribute();
            $users = User::select('id', 'name')->whereHas('branches', function($q) use ($branch_id) { $q->whereIn('branch_id', $branch_id); })->get();
        }else{
            $users = User::all('id', 'name');
        }

        return view('admin.task.create', compact("tasks","users"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStoreRequest $request)
    {
        try {

            $tasks = new tasks();
            $tasks->title = $request->title;
            $tasks->description = $request->description;
            $tasks->created_by = auth()->user()->id;
            $tasks->updated_by = auth()->user()->id;
            $tasks->save();

            $tasks->users()->attach($request->user_id);

            $tasks->taskStatus()->attach(1); //Add Task Status ID 1 = Unaccepted

            //Session::flash('success', 'Task was created successfully.');
            //return redirect()->route('tasks.index');

            return response()->json([
                'success' => 'Tasks was created successfully.' // for status 200
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
     * @param  \App\tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function show(tasks $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(tasks $task)
    {
        if(!auth()->user()->hasRole('superadmin')){
            $branch_id = auth()->user()->getBranchIdsAttribute();
            $users = User::select('id', 'name')->whereHas('branches', function($q) use ($branch_id) { $q->whereIn('branch_id', $branch_id); })->get();
        }else{
            $users = User::pluck('id', 'name')->toArray();
        }

        $taskUsers = $task->users->pluck('id')->toArray();
        $users = array_filter(array_replace(array_fill_keys($taskUsers, null), array_flip($users)));
        $users = array_flip($users);

        return view('admin.task.edit', compact('task','users', 'taskUsers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, tasks $task)
    {
        try {

            if (empty($task)) {
                //Session::flash('failed', 'Task Update Denied');
                //return redirect()->back();
                return response()->json([
                    'error' => 'Task update denied.' // for status 200
                ]);   
            }

            $task->title = $request->title;
            $task->description = $request->description;
            $task->updated_by = auth()->user()->id;
            $task->save();
            $task->users()->detach();

            $task->users()->syncWithoutDetaching($request->user_id);
            //Session::flash('success', 'A Wiki Blog updated successfully.');
            //return redirect('admin/wikiBlog');

            return response()->json([
                'success' => 'Task update successfully.' // for status 200
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
     * @param  \App\tasks  $tasks
     * @return \Illuminate\Http\Response
     */
    public function destroy(tasks $task)
    {
        // delete wiki blog
        $task->delete();

        //return redirect('admin/task')->with('delete', 'wiki blog deleted successfully.');
        return response()->json([
            'delete' => 'task deleted successfully.' // for status 200
        ]);
    }
}
