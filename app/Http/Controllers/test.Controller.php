<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Request as ModelRequest;
use App\Models\Profile;
use App\Models\major as Major;
use App\Models\Scholarship;
use App\Models\DailyTracker;
use Morilog\Jalali\Jalalian;
use Carbon\Carbon;

class UnifiedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:user,admin,master')->only([
            'myrequests', 'addoreditrequests', 'storerequest', 'updaterequest',
            'deleterequest', 'message', 'addmessage', 'storemessage', 'uploadFile','cancel','storecard'
        ]);
        $this->middleware('role:admin,master')->only([
            'allrequests', 'requestdetail', 'accept', 'reject', 'epointment',
            'acceptes', 'scholarship', 'users', 'deleteuser'
        ]);
        $this->middleware('role:master')->only(['admin', 'nadmin']);
        $this->middleware('role:admin')->only([
            'addprofile', 'storeprofile', 'editprofile', 'updateprofile'
        ]);
        ;
    }
public function storecard(Request $req , $id){

    $request = ModelRequest::findOrFail($id);
    $request->cardnumber = $req->cardnumber;
    $request->update();

    return redirect()->route('unified.myrequests');
}

}
