<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UsersCreateUpdateRequest;
use App\Models\Entitymst;
use App\Models\Role;
use App\Services\V1\UserService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use CommonTrait;
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $baseurl = route('admin.users.index');
            $data = Entitymst::with(['role' => function ($query) {
                $query->select('id', 'name');
            }])->notAdmin();
            if ($request->order == null) {
                $data->orderBy('created_at', 'desc');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'status_text'])
                ->make(true);
        }
        $title =  'Users';
        $entityTypes = Entitymst::ENTITYTYPES;
        return view('admin.users.index', compact('title', 'entityTypes'));
    }

    /**
     * Store a newly created or update resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersCreateUpdateRequest $request)
    {

        if (isset($request->id) && $request->id > 0) { //update data
            $user = $this->userService->update($request, $request->id);
        } else { //add data
            $request->request->add([
                'entity_type' => Entitymst::ENTITYUSER,
                'role_id' => Role::ROLEUSER,
            ]);
            $user  = $this->userService->store($request);
        }
        if (!$user['status']) {
            return response()->json($user, 401);
        }
        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userService->show($id);
        if (!$user['status']) {
            return response()->json($user, 401);
        }
        return response()->json($user, 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userService->destroy($id);
        if (!$user['status']) {
            return response()->json($user, 401);
        }
        return response()->json($user, 200);
    }
}
