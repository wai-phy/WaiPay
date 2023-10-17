<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    //dashboard home
    public function adminHome(){
        return view('backend.home');
    }

    //list page
    public function adminList(){
        return view('backend.admin_user.admin_list');
    }

    //dataTable
    public function DataTable(Request $request){
        if ($request->ajax()) {
            $data = User::where('role','admin')->select('*');
            return Datatables::of($data)
                ->editColumn('user_agent',function($each){
                    if($each->user_agent){
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
                // ->editColumn('created_at',function($each){
                //     return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
                // })
                // ->editColumn('updated_at',function($each){
                //     return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
                // })
                ->addColumn('action',function($each){
                    $edit_icon = '<a class="text-warning" href="'.route('admin#edit',$each->id).'"><i class="fa-solid fa-pen-to-square me-3 fs-4"></i></a>';
                    $delete_icon = '<a class="text-danger delete"  data-url="'.route('admin#delete',$each->id).'" data-id="'.$each->id.'" href="#"><i class="fa-solid fa-trash me-3 fs-4"></i></a>';

                    return '<div>' . $edit_icon . $delete_icon . '</div>';
                })
                ->rawColumns(['user_agent','action'])
                ->make(true);

        }

    }

    //admin create
    public function adminCreate(){
        return view('backend.admin_user.create');
    }

    //admin store
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required|min:6|max:15',
        ]);
        $admin_user = new User();
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->role = $request->role;
        $admin_user->password = Hash::make($request->password);
        $admin_user->save();

        return redirect()->route('admin#List')->with('create','Admin Has Been created successfully');
        
    }

    //admin edit page
    public function edit($id){
        $admin_user = User::where('id',$id)->first();
        return view('backend.admin_user.update',compact('admin_user'));
    }

    //admin user update
    public function update(Request $request, $id){

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' .$id,
            'phone' => 'required|unique:users,phone,' .$id,
        ]);
        $admin_data =$this->admin_data($request);

        User::where('id',$id)->update($admin_data);
        return redirect()->route('admin#List')->with('update','Admin Has Been updated successfully');
        
    }

        //getAdmin Data
    private function admin_data($request){
        return [

            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];
     }

     //admin delete
     public function destroy($id){

        User::where('id',$id)->delete();
        return 'success';

     }


    

}
