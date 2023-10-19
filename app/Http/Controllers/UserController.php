<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Helpers\UUIDGenerate;


class UserController extends Controller
{


    //list page
    public function index()
    {
        return view('backend.user.user_list');
    }

    //dataTable
    public function serverData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'user')->select('*');
            return Datatables::of($data)
                ->editColumn('user_agent', function ($each) {
                    if ($each->user_agent) {
                        $agent = new Agent();
                        $agent->setUserAgent($each->user_agent);
                        $device = $agent->device();
                        $platform = $agent->platform();
                        $browser = $agent->browser();

                        return "<table class='table table-bordered'>
                        <tr>
                          <td>Device</td>
                          <td> $device </td>
                        </tr>
                        <tr>
                          <td>Platform</td>
                          <td> $platform </td>
                        </tr>
                        <tr>
                          <td>Browser</td>
                          <td> $browser </td>
                        </tr>
                      </table>";
                    }

                    return "-";
                })
                ->editColumn('created_at', function ($each) {
                    return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('updated_at', function ($each) {
                    return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
                })
                ->addColumn('action', function ($each) {
                    $edit_icon = '<a class="text-warning" href="' . route('users.edit', $each->id) . '"><i class="fa-solid fa-pen-to-square me-3 fs-4"></i></a>';
                    $delete_icon = '<a class="text-danger delete"  data-url="' . route('users.destroy', $each->id) . '" data-id="' . $each->id . '" href="#"><i class="fa-solid fa-trash me-3 fs-4"></i></a>';

                    return '<div>' . $edit_icon . $delete_icon . '</div>';
                })
                ->rawColumns(['user_agent', 'action'])
                ->make(true);
        }
    }

    //user create
    public function create()
    {
        return view('backend.user.create');
    }

    //user store
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'password' => 'required|min:6|max:15',
            ]);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;
            $user->password = Hash::make($request->password);
            $user->save();

            Wallet::firstOrCreate([
                'user_id' => $user->id
            ], [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0,
            ]);

            DB::commit();
            return redirect()->route('users.index')->with('create', 'User  Has Been created successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Went Wrong!!!.' . $e->getMessage()])->withInput();
        }
    }

    //user  edit page
    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('backend.user.update', compact('user'));
    }

    //user  user update
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'phone' => 'required|unique:users,phone,' . $id,
            ]);
            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->update();

            Wallet::firstOrCreate([
                'user_id' => $user->id
            ], [
                'account_number' => UUIDGenerate::accountNumber(),
                'amount' => 0,
            ]);

            DB::commit();

            return redirect()->route('users.index')->with('update', 'User  Has Been updated successfully');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail' => 'Something Went Wrong!!!.' . $e->getMessage()])->withInput();
        }
    }


    //user  delete
    public function destroy($id)
    {

        User::where('id', $id)->delete();
        return 'success';
    }
}
