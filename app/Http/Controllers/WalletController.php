<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use \Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    //wallet index
    public function index(){
        return view('backend.wallet.index');
    }

    //datatables
    public function serverData(Request $request)
    {
        
            $wallet = Wallet::with('user');
            
            return Datatables::of($wallet)
                ->addColumn('account_person',function($each){
                    $user = $each->user;
                    if($user){
                        return "<p>Name: $user->name</p><p>Email:$user->email </p><p>Phone: $user->phone</p>";
                    }
                    return "-";
                })
                ->editColumn('amount',function($each){
                    return number_format($each->amount,2);
                })
                ->editColumn('created_at',function($each){
                    return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
                })
                ->editColumn('updated_at',function($each){
                    return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
                })
                ->rawColumns(['account_person'])
                ->make(true);
    }

    //add amount wallet
    public function addAmount(){
        $users = User::where('role','user')->orderBy('name')->get();
        return view('backend.wallet.add_amount',compact('users'));
    }

    //add amount store
    public function addAmountStore(Request $request){
        $request->validate(
            [
            'user_id' => 'required',
            'amount' => 'required|integer'
        ],
        );

        if($request->amount < 1000 ){
            return back()->withErrors(['amount'=>'The amount must at least 1000 MMK.'])->withInput();
        }

        DB::beginTransaction();

        try {
            $to_account = User::with('wallet')->where('id',$request->user_id)->firstOrFail();
            $to_account_wallet = $to_account->wallet;
            $to_account_wallet->increment('amount',$request->amount);
            $to_account_wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $to_account_transaction = new Transaction();
            $to_account_transaction->ref_no = $ref_no ;
            $to_account_transaction->trx_id = UUIDGenerate::trxId();
            $to_account_transaction->user_id = $to_account->id;
            $to_account_transaction->type = 1;
            $to_account_transaction->amount = $request->amount;
            $to_account_transaction->source_id = 0;
            $to_account_transaction->description = $request->description;
            $to_account_transaction->save();

            DB::commit();
            return redirect()->route('wallet.index')->with(['create'=>'Successfully transfered']);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->withErrors(['fail'=>'Something wrong' . $e])->withInput();
        }

    }
}
