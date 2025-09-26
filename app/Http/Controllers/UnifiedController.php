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
            'deleterequest', 'message', 'addmessage', 'storemessage'
        ]);
        $this->middleware('role:admin,master')->only([
            'allrequests', 'requestdetail', 'accept', 'reject', 'epointment',
            'acceptes', 'scholarship', 'users', 'deleteuser'
        ]);
        $this->middleware('role:master')->only(['admin', 'nadmin']);
        $this->middleware('role:admin')->only([
            'addprofile', 'storeprofile', 'editprofile', 'updateprofile'
        ]);
    }

    // My Requests - All Roles
    public function myrequests()
    {
        $userRole = Auth::user()->role;

        if ($userRole === 'user') {
            $requests = Auth::user()->requests()->where('story', '!=', 'cancel')->get();
        } else {
            // Admin/Master see their own requests if any
            $requests = Auth::user()->requests()->where('story', '!=', 'cancel')->get();
        }

        return view('unified.myrequests', compact('requests'));
    }

    // Add or Edit Request - All Roles
    public function addoreditrequests($id = null)
    {
        $request = null;
        if ($id) {
            if (Auth::user()->role === 'user') {
                $request = Auth::user()->requests()->findOrFail($id);
            } else {
                $request = ModelRequest::findOrFail($id);
            }
        }

        return view('unified.addoreditrequests', compact('request'));
    }

    public function storerequest(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:25',
            'female' => 'required|string|max:50',
            'phone' => 'required|string|max:15',
            'nationalcode' => 'required|string|max:10',
            'grade' => 'required|string|max:25',
            'imgpath' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'abouts.*' => 'nullable|string|max:191',
            'address' => 'required|string'
        ]);

        if ($request->hasFile('imgpath')) {
            $path = $request->file('imgpath')->store('userimage', 'private');
        }

        $createdRequest = Auth::user()->requests()->create([
            'name' => $data['name'],
            'female' => $data['female'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'nationalcode' => $data['nationalcode'],
            'grade' => $data['grade'],
            'imgpath' => $path ?? 'userimage/default.png'
        ]);

        if (!empty($data['abouts'])) {
            foreach ($data['abouts'] as $about) {
                if (!empty($about)) {
                    $createdRequest->aboutreqs()->create(['about' => $about]);
                }
            }
        }

        return redirect()->route('unified.myrequests')->with('success', 'درخواست با موفقیت ثبت شد.');
    }

    public function updaterequest(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:25',
            'female' => 'required|string|max:50',
            'phone' => 'required|string|max:15',
            'nationalcode' => 'required|string|max:10',
            'grade' => 'required|string|max:25',
            'imgpath' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string'
        ]);

        if (Auth::user()->role === 'user') {
            $requestModel = Auth::user()->requests()->findOrFail($id);
        } else {
            $requestModel = ModelRequest::findOrFail($id);
        }

        if ($request->hasFile('imgpath')) {
            if ($requestModel->imgpath != 'userimage/default.png') {
                Storage::disk('private')->delete($requestModel->imgpath);
            }
            $path = $request->file('imgpath')->store('userimage', 'private');
            $data['imgpath'] = $path;
        }

        $requestModel->update($data);
        return redirect()->route('unified.myrequests')->with('success', 'درخواست با موفقیت ویرایش شد.');
    }

    public function deleterequest($id)
    {
        if (Auth::user()->role === 'user') {
            $requestModel = Auth::user()->requests()->findOrFail($id);
            $requestModel->update(['story' => 'cancel']);
        } else {
            $requestModel = ModelRequest::findOrFail($id);
            $requestModel->delete();
        }

        return redirect()->route('unified.myrequests')->with('success', 'درخواست با موفقیت حذف شد.');
    }

    // All Requests - Admin/Master Only
    public function allrequests()
    {
        $requests = ModelRequest::with('user')->where('story', '!=', 'cancel')->get();
        return view('unified.allrequests', compact('requests'));
    }

    // Request Detail - Admin/Master Only
    public function requestdetail($id)
    {
        $userrequest = ModelRequest::with('user')->findOrFail($id);

        if ($userrequest->story == 'submit') {
            $userrequest->update(['story' => 'check']);
        }

        return view('unified.requestdetail', compact('userrequest'));
    }

    // Status Update Methods
    public function accept($id)
    {
        $userrequest = ModelRequest::findOrFail($id);
        $userrequest->update(['story' => 'accept']);
        DailyTracker::create([
            'request_id' => $id,
            'start_date' => Carbon::now()->startOfDay(),
            'max_days' => 31
        ]);
        return redirect()->route('unified.allrequests')->with('success', 'درخواست پذیرفته شد');
    }

    public function reject($id)
    {
        $userrequest = ModelRequest::findOrFail($id);
        $userrequest->update(['story' => 'reject']);

        return redirect()->route('unified.allrequests')->with('success', 'درخواست رد شد');
    }

    public function epointment(Request $request, $id)
    {
        if (Auth::user()->role === 'user') {
            return redirect()->route('unified.myrequests');
        }

        $userrequest = ModelRequest::with('user')->findOrFail($id);

        try {
 // تبدیل مستقیم با Jalalian
        $gregorianDate = Jalalian::fromFormat('Y/m/d H:i:s', $jalaliDateTime)->toCarbon();
            $userrequest->update([
                'story' => 'epointment',
                'date' => $gregorianDate
            ]);
            return redirect()->route('unified.allrequests')->with('success', 'زمان ملاقات تعیین شد');
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطا تو تاریخ !',
                'received_date' =>  $jalaliDate,
                'format_issue' => 'فرمت باید باشه: ساعت:دقیقه سال-ماه-روز (مثل 11:45 1404-12-3)',
                'error_message' => $e->getMessage()
            ], 422);
        }
    }

    // Message - All Roles
    public function message($id)
    {
        $scholarships = Scholarship::with('request')->where('request_id', $id)->get();
        return view('unified.message', compact('scholarships', 'id'));
    }

    // Add Message - All Roles
    public function addmessage($id)
    {
        return view('unified.addmessage', compact('id'));
    }

    public function storemessage(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'required|string',
            'story' => 'required|in:thanks,warning,message,scholarship',
            'price' => 'nullable|integer',
        ]);

        $userRole = Auth::user()->role;

        if ($userRole === 'user') {
            $data['profile_id'] = null;
        } else {
            if ($userRole === 'admin') {
                $data['profile_id'] = Auth::user()->profile->id;
            } else {
                $data['profile_id'] = null;
                $data['ismaster'] = true;
            }
        }

        $data['request_id'] = $id;

        // If it's a scholarship, handle DailyTracker
        if ($data['story'] === 'scholarship') {
            $tracker = DailyTracker::where('request_id', $id)->first();
            if ($tracker) {
                $tracker->start_date = Carbon::now()->startOfDay();
                $tracker->save();
            }

            Scholarship::create($data);
            return redirect()->route('unified.acceptes')->with('success', 'بورسیه با موفقیت تعیین شد');
        }

        // Regular message
        Scholarship::create($data);
        return redirect()->route('unified.message', $id)->with('success', 'پیام با موفقیت ارسال شد');
    }

    // Accepted Requests - Admin/Master Only
    public function acceptes()
    {
        if (Auth::user()->role === 'user') {
            return redirect()->route('unified.myrequests');
        }

        $requests = ModelRequest::with('user')->where('story', 'accept')->get();
        return view('unified.acceptes', compact('requests'));
    }

    // Users Management - Admin/Master Only
    public function users()
    {
        if (Auth::user()->role === 'user') {
            return redirect()->route('unified.myrequests');
        }

        $userRole = Auth::user()->role;

        if ($userRole === 'admin') {
            // Admin can only see users with 'user' role
            $users = User::where('role', 'user')->get();
        } else {
            // Master can see users and admins (not other masters)
            $users = User::where('role', '!=', 'master')->get();
        }

        return view('unified.users', compact('users'));
    }
    public function userdetail($id){
        $user = User::with('profile')->find($id);
        return view('unified.userdetail',compact('user'));
    }
    public function deleteuser($id)
    {
        if (Auth::user()->role === 'user') {
            return redirect()->route('unified.myrequests');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('unified.users')->with('success', 'کاربر حذف شد');
    }

    // Master Only - User Role Management
    public function admin($id)
    {
        if (Auth::user()->role !== 'master') {
            return redirect()->route('unified.myrequests');
        }

        $user = User::findOrFail($id);
        $user->role = 'admin';
        $user->save();

        return redirect()->back()->with('success', 'کاربر به ادمین تبدیل شد');
    }

    public function nadmin($id)
    {
        if (Auth::user()->role !== 'master') {
            return redirect()->route('unified.myrequests');
        }

        $user = User::findOrFail($id);
        $user->role = 'user';
        $user->save();

        return redirect()->back()->with('success', 'ادمین به کاربر تبدیل شد');
    }

    // Add Profile - Admin Only
    public function addprofile()
    {
        $profile = Auth::user()->profile;

        return view('unified.addprofile',compact('profile'));
    }

    public function storeprofile(Request $request)
    {
        $profile = $request->validate([
            'name' => 'required|string|max:25',
            'female' => 'required|string|max:50',
            'nationalcode' => 'required|string|max:14',
            'phone' => 'required|string|max:15',
            'position' => 'required|string|max:50',
            'imgpath' => 'nullable|file'
        ]);

        if ($request->hasFile('imgpath')) {
            $path = $request->file('imgpath')->store('userimage', 'private');
            $profile['imgpath'] = $path;
        }

        $profile['user_id'] = Auth::user()->id;
        Profile::create($profile);

        return redirect()->route('unified.myrequests')->with('success', 'پروفایل با موفقیت تکمیل شد');
    }

    public function editprofile($id)
    {
        $profile = Profile::findOrFail($id);
        return view('unified.addprofile', compact('profile'));
    }

    public function updateprofile(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('unified.myrequests');
        }

        $data = $request->validate([
            'name' => 'string|max:25',
            'female' => 'string|max:50',
            'nationalcode' => 'string|max:16',
            'phone' => 'string|max:15',
            'position' => 'string|max:50',
            'imgpath' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = Profile::findOrFail($id);

        if ($request->hasFile('imgpath')) {
            if ($profile->imgpath !== 'userimage/default.png') {
                Storage::disk('private')->delete($profile->imgpath);
            }
            $path = $request->file('imgpath')->store('userimage', 'private');
            $data['imgpath'] = $path;
        } else {
            unset($data['imgpath']);
        }

        $profile->update($data);

        return redirect()->route('unified.addprofile')->with('success', 'پروفایل با موفقیت ویرایش شد');
    }
    public function requestform()
    {
        $majors = Major::all();
        return view('unified.requestsForm.index', compact('majors'));
    }
public function storerequestform(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:75',
        'birthdate' => 'required|string',
        'nationalcode' => 'required|string|max:10',
        'phone' => 'required|string|max:11',
        'telephone' => 'nullable|string|max:11',
        'rental' => 'required|string|in:0,1', // اصلاح شد - فقط 0 (ملکی) یا 1 (استیجاری)
        'grade' => 'required|string|max:25',
        'major_id' => 'nullable|exists:majors,id',
        'school' => 'required|string|max:75',
        'last_score' => 'required|numeric|between:0,20',
        'principal' => 'required|string|max:75',
        'school_telephone' => 'nullable|string|max:11',
        'father_name' => 'required|string|max:75',
        'father_phone' => 'required|string|max:11',
        'father_job' => 'required|string|max:75',
        'mother_name' => 'required|string|max:75',
        'mother_phone' => 'required|string|max:11',
        'mother_job' => 'required|string|max:75',
        'address' => 'required|string',
        'father_job_address' => 'required|string',
        'mother_job_address' => 'required|string',
        'father_income' => 'required|string', // Because of comma formatting
        'mother_income' => 'required|string', // Because of comma formatting
        'siblings_count' => 'required|integer|min:1',
        'siblings_rank' => 'required|integer|min:1',
        'english_proficiency' => 'required|integer|between:0,100', // اصلاح شد - هر عددی از 0 تا 100
        'know' => 'required|string|max:191',
        'counseling_method' => 'required|string|max:191',
        'why_counseling_method' => 'nullable|string', // اصلاح شد - غیر اجباری
        'motivation' => 'required|string|min:30',
        'spend' => 'required|string',
        'how_am_i' => 'required|string',
        'favorite_major' => 'required|string',
        'future' => 'required|string',
        'help_others' => 'required|string',
        'suggestion' => 'nullable|string',
        'imgpath' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'gradesheetpath' => 'required|file|mimes:jpeg,png,jpg,gif,pdf|max:5048',
        'abouts.*' => 'nullable|string|max:191'
    ]);

    // تبدیل تاریخ شمسی به میلادی
    $data['birthdate'] = Jalalian::fromFormat('Y/m/d', $data['birthdate'])->toCarbon();

    // حذف کاما از درآمدها و تبدیل به integer
    $data['father_income'] = (int)str_replace(',', '', $data['father_income']);
    $data['mother_income'] = (int)str_replace(',', '', $data['mother_income']);

    // آپلود فایل‌ها
    $imgPath = null;
    $gradesheetPath = null;

    if ($request->hasFile('imgpath')) {
        $imgPath = $request->file('imgpath')->store('userimage', 'private');
    }

    if ($request->hasFile('gradesheetpath')) {
        $gradesheetPath = $request->file('gradesheetpath')->store('userimage', 'private');
    }

    // ایجاد درخواست
    $createdRequest = Auth::user()->requests()->create([
        'name' => $data['name'],
        'birthdate' => $data['birthdate'],
        'nationalcode' => $data['nationalcode'],
        'phone' => $data['phone'],
        'telephone' => $data['telephone'] ?? null,
        'rental' => $data['rental'],
        'grade' => $data['grade'],
        'major_id' => $data['major_id'] ?? null,
        'school' => $data['school'],
        'last_score' => $data['last_score'],
        'principal' => $data['principal'],
        'school_telephone' => $data['school_telephone'] ?? null,
        'father_name' => $data['father_name'],
        'father_phone' => $data['father_phone'],
        'father_job' => $data['father_job'],
        'mother_name' => $data['mother_name'],
        'mother_phone' => $data['mother_phone'],
        'mother_job' => $data['mother_job'],
        'address' => $data['address'],
        'father_job_address' => $data['father_job_address'],
        'mother_job_address' => $data['mother_job_address'],
        'father_income' => $data['father_income'],
        'mother_income' => $data['mother_income'],
        'siblings_count' => $data['siblings_count'],
        'siblings_rank' => $data['siblings_rank'],
        'english_proficiency' => $data['english_proficiency'],
        'know' => $data['know'],
        'counseling_method' => $data['counseling_method'],
        'why_counseling_method' => $data['why_counseling_method'],
        'motivation' => $data['motivation'],
        'spend' => $data['spend'],
        'how_am_i' => $data['how_am_i'],
        'favorite_major' => $data['favorite_major'],
        'future' => $data['future'],
        'help_others' => $data['help_others'],
        'suggestion' => $data['suggestion'] ?? null,
        'imgpath' => $imgPath,
        'gradesheetpath' => $gradesheetPath,
        'story' => 'submit' // وضعیت اولیه
    ]);

    // ذخیره مهارت‌ها و افتخارات (اگر وجود دارد)
    if (!empty($data['abouts'])) {
        foreach ($data['abouts'] as $about) {
            if (!empty($about)) {
                $createdRequest->aboutreqs()->create([
                    'title' => 'مهارت', // یا هر عنوان مناسب
                    'description' => $about
                ]);
            }
        }
    }

    return redirect()->route('unified.myrequests')->with('success', 'درخواست با موفقیت ثبت شد.');
}
public function storecard(Request $req , $id){

    $request = ModelRequest::findOrFail($id);
    $request->cardnumber = $req->cardnumber;
    $request->update();

    return redirect()->route('unified.myrequests');
}
}
