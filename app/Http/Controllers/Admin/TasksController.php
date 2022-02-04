<?php

namespace App\Http\Controllers\admin;

use App\tasks;
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
                                    ]);

            return Datatables::eloquent($data)
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

                ->rawColumns(['action'])
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
        return view('admin.task.create', compact("tasks"));
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
            $tasks->title = $tasks->title;
            $tasks->description = $tasks->description;
            $tasks->created_by = auth()->user()->id;
            $tasks->updated_by = auth()->user()->id;
            $tasks->save();

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
        return view('admin.task.edit', compact('task'));
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
            $task->status = $request->status;
            $task->updated_by = auth()->user()->id;
            $task->save();

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
