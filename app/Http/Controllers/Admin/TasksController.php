<?php

namespace App\Http\Controllers\admin;

use App\tasks;
use App\User;
use App\taskStatus;
use App\Tasks_has_taskstatus;
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
use Carbon\Carbon;


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
                                        'recurring',
                                        'due_date',
                                        'created_at',
                                        'updated_at',
                                    ])

                            ->with([
                                    'users',
                                    'Tasks_has_taskstatus' => function ($query) { 
                                            $query->orderBy('created_at', 'desc')
                                                    ->whereDate('created_at', Carbon::today())
                                                    ->with('creator','taskStatus');
                                            },
                                    'taskStatus' => function ($query) { 
                                            $query->orderBy('pivot_created_at', 'desc')->first();
                                    }
                                    ]);
                                    //->get();
                                    //dd($data);
            
            return Datatables::eloquent($data)
                ->addColumn('users_avatars', function ($data) {
                    $users='<div class="avatars_overlapping">';
  
                    foreach ($data->users->reverse() as $key => $value) {
                       $users.='<span class="avatar_overlapping"><p tooltip="'.$value->name.'" flow="up"><img src="'.$value->getImageUrlAttribute($value->id).'" width="50" height="50" /></p></span>';
                    }

                    return $users.='</div>';
                })
                ->addColumn('due_date_status', function ($data) {
                    if($data->recurring=='Daily'){
                        $due_date_status = Carbon::now()->format('Y-m-d');
                    }else{
                        $due_date_status = $data->due_date;
                    }
                
                    return $due_date_status;
                    
                })
                ->addColumn('taskAccepted', function ($data) {
                    if(isset($data->Tasks_has_taskstatus[0]) && isset($data->Tasks_has_taskstatus[0]->creator)){
                        if($data->Tasks_has_taskstatus[0]->taskstatuses_id==1){
                            return $taskAccepted = 'Not Accepted';
                        }
                        $taskAccepted = '<img src="'.$data->Tasks_has_taskstatus[0]->creator->getImageUrlAttribute($data->Tasks_has_taskstatus[0]->creator->id).'" alt="user_id_'.$data->Tasks_has_taskstatus[0]->creator->id.'" class="profile-user-img-small img-circle"> '. $data->Tasks_has_taskstatus[0]->creator->name;
                        
                    }else{
                        $taskAccepted = 'Not Accepted';
                    }
                
                    return $taskAccepted;
                    
                })
                ->addColumn('status', function ($data) {
                        $class ='text-danger';
                        $currentStatusID = 1;
                        if(isset($data->Tasks_has_taskstatus[0]) && isset($data->Tasks_has_taskstatus[0]->taskStatus)){
                            $taskActiveStatus = $data->Tasks_has_taskstatus[0]->taskStatus->name;
                            $currentStatusID = $data->Tasks_has_taskstatus[0]->taskStatus->id;
                            $class =$data->Tasks_has_taskstatus[0]->taskStatus->class;
                        }else{
                            $taskActiveStatus = 'Unaccepted';
                        }

                        $allTaskStatus = taskStatus::all();
                        $Status= '<div class="dropdown action-label">
                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-dot-circle-o '.$class.'"></i> '.$taskActiveStatus.' </a><div class="dropdown-menu dropdown-menu-right" style="">';

                        foreach ($allTaskStatus as $allTaskStatus) {
                            $Status.= '<a class="dropdown-item" href="#" onclick="funChangeStatus('.$data->id.','.$allTaskStatus->id.','.$currentStatusID.'); return false;"><i class="fa fa-dot-circle-o '.$allTaskStatus->class.'"></i> '.$allTaskStatus->name.'</a>';
                        }
                        
                        $Status.= '</div></div>';
                        return $Status;
                    })
                ->addColumn('action', function ($data) {
                    
                    $html='';
                    if (auth()->user()->can('edit Task')){
                        $html.= '<a href="'.  route('admin.task.edit', ['task' => $data->id]) .'" class="btn btn-success btn-sm float-left mr-1"  id="popup-modal-button"><span tooltip="Edit" flow="left"><i class="fas fa-edit"></i></span></a>';
                    }

                    if (auth()->user()->can('delete Task')){
                        $html.= '<form method="post" class="float-left delete-formleft mr-1" action="'.  route('admin.task.destroy', ['task' => $data->id ]) .'"><input type="hidden" name="_token" value="'. Session::token() .'"><input type="hidden" name="_method" value="delete"><button type="submit" class="btn btn-danger btn-sm"><span tooltip="Delete" flow="up"><i class="fas fa-trash"></i></span></button></form>';
                    }

                    if (auth()->user()->can('view Task')){
                        $html.= '<a href="'.  route('admin.task.show', ['task' => $data->id]) .'" id="popup-modal-button"  class="btn btn-danger btn-sm"><span tooltip="View" flow="up"><i class="fas fa-eye"></i></span></a>';
                    }

                    return $html; 
                
                })
                ->rawColumns(['users_avatars', 'due_date_status', 'action', 'taskAccepted','status'])
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

        $recurring = ['Daily', 'Weekly', 'Bi-Weekly', 'Monthly', 'Quarterly', 'Yearly'];

        return view('admin.task.create', compact("tasks","users","recurring"));
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
            $tasks->recurring = $request->recurring;
            $tasks->due_date = $request->due_date;
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
        $data = tasks::with([
                            'users',
                            'Tasks_has_taskstatus' => function ($query) { 
                                    $query->orderBy('created_at', 'desc')
                                            ->whereDate('created_at', Carbon::today())
                                            ->with('creator','taskStatus');
                                    },
                            'taskStatus' => function ($query) { 
                                    $query->orderBy('pivot_created_at', 'desc')->first();
                            }
                            ])
                    ->where('id',$task->id)
                    ->first();
        return view('admin.task.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\tasks  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(tasks $task)
    {
        $taskUsers = $task->users->pluck('id')->toArray();
        if(!auth()->user()->hasRole('superadmin')){
            $branch_id = auth()->user()->getBranchIdsAttribute();
            $users = User::whereHas('branches', function($q) use ($branch_id) { $q->whereIn('branch_id', $branch_id); })->get()->pluck('id', 'name');
            $users = array_filter(array_replace(array_fill_keys($taskUsers, null), array_flip($users->toArray())));
        }else{
            $users = User::pluck('id', 'name')->toArray();
            $users = array_filter(array_replace(array_fill_keys($taskUsers, null), array_flip($users)));
        }

        $users = array_flip($users);
        $recurring = ['Daily', 'Weekly', 'Bi-Weekly', 'Monthly', 'Quarterly', 'Yearly'];
        return view('admin.task.edit', compact('task','users', 'taskUsers', 'recurring'));
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
            $task->recurring = $request->recurring;
            $task->due_date = $request->due_date;
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
        // delete task
        $task->delete();

        //return redirect('admin/task')->with('delete', 'task deleted successfully.');
        return response()->json([
            'delete' => 'task deleted successfully.' // for status 200
        ]);
    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function change_status(Request $request)
    {
        try {
            $currentStatusID = $request->currentStatusID;
            $task_id = $request->id;
            $taskstatuses_id = $request->status;
            $user_id = auth()->user()->id;
            $tasks = tasks::with([
                                    'users' => function ($query) use ($user_id) { 
                                            $query->find($user_id);
                                        }
                                    ])->find($task_id);
            if (empty($tasks)) {
                return response()->json([
                    'error' => 'Task update denied.' // for status 200
                ]);   
            }

            if (empty($tasks->users[0]) ) {
                return response()->json([
                    'error' => 'Task update denied because you are not allowed to accept this task !!' // for status 200
                ]);   
            }

            
            if($currentStatusID!=$taskstatuses_id){
                $tasks->taskStatus()->attach($taskstatuses_id, ['created_by' => auth()->user()->id,'updated_by' => auth()->user()->id]); //Add Task Status ID 1 = Unaccepted
            }
            
            //Session::flash('success', 'A Task updated successfully.');
            //return redirect('admin/print_buttons');

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
}
