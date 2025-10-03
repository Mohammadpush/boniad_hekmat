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

        return view('unified.user.myrequests', compact('requests'));
    }

    // Add or Edit Request - All Roles

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
        return view('unified..admin.allrequests', compact('requests'));
    }

    // Request Detail - Admin/Master Only
    public function requestdetail($id)
    {
        $userrequest = ModelRequest::with('user')->findOrFail($id);

        if ($userrequest->story == 'submit') {
            $userrequest->update(['story' => 'check']);
        }

        return view('unified.admin.requestdetail', compact('userrequest'));
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
        return view('unified.user.message', compact('scholarships', 'id'));
    }

    // Add Message - All Roles
    public function addmessage($id)
    {
        return view('unified.user.addmessage', compact('id'));
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
        return view('unified.admin.acceptes', compact('requests'));
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

        return view('unified.admin.users', compact('users'));
    }
    public function userdetail($id){
        $user = User::with('profile')->find($id);
        return view('unified.admin.userdetail',compact('user'));
    }
    public function deleteuser($id)
    {
        if (Auth::user()->role === 'user') {
            return redirect()->route('unified.myrequests');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('unified.admin.users')->with('success', 'کاربر حذف شد');
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

        return view('unified.admin.addprofile',compact('profile'));
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
        return view('unified.admin.addprofile', compact('profile'));
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

        return redirect()->route('unified.admin.addprofile')->with('success', 'پروفایل با موفقیت ویرایش شد');
    }
    public function requestform()
    {
        $majors = Major::all();
        return view('unified.user.addrequest', compact('majors'));
    }
        public function editrequest($id)
    {
        $userrequest = ModelRequest::findOrFail($id);
        $majors = Major::all();
        return view('unified.user.editrequest', compact('majors','userrequest'));
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

/**
 * بروزرسانی فیلد مشخص از درخواست
 */
public function updateRequestField(Request $request)
{
    \Log::info('Update Request Field - Received Request', [
        'request_id' => $request->request_id,
        'field_name' => $request->field_name,
        'field_value' => $request->field_value,
        'user_id' => Auth::id(),
        'all_data' => $request->all()
    ]);

    try {
        $request->validate([
            'request_id' => 'required|integer|exists:requests,id',
            'field_name' => 'required|string',
            'field_value' => 'required|string'
        ]);

        $requestModel = ModelRequest::findOrFail($request->request_id);

        // بررسی اجازه دسترسی
        $user = Auth::user();
        if ($user->role === 'user' && $requestModel->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'شما اجازه ویرایش این درخواست را ندارید'
            ], 403);
        }

        // لیست فیلدهای مجاز برای ویرایش
        $allowedFields = [
            'name', 'birthdate', 'nationalcode', 'phone', 'telephone',
            'grade', 'school', 'last_score', 'principal', 'school_telephone',
            'rental', 'address', 'father_name', 'father_phone', 'father_job',
            'father_income', 'father_job_address', 'mother_name', 'mother_phone',
            'mother_job', 'mother_income', 'mother_job_address', 'siblings_count',
            'siblings_rank', 'english_proficiency', 'know', 'counseling_method',
            'why_counseling_method', 'motivation', 'spend', 'how_am_i',
            'favorite_major', 'future', 'help_others', 'suggestion'
        ];

        if (!in_array($request->field_name, $allowedFields)) {
            return response()->json([
                'success' => false,
                'message' => 'این فیلد قابل ویرایش نیست'
            ], 400);
        }

        // اعتبارسنجی خاص برای هر فیلد
        $fieldValue = $request->field_value;

        switch ($request->field_name) {
            case 'name':
                if (empty($fieldValue) || strlen(trim($fieldValue)) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'نام باید حداقل 2 کاراکتر باشد'
                    ], 400);
                }
                if (strlen($fieldValue) > 75) {
                    return response()->json([
                        'success' => false,
                        'message' => 'نام نباید بیشتر از 75 کاراکتر باشد'
                    ], 400);
                }
                break;

            case 'birthdate':
                if (empty($fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'تاریخ تولد الزامی است'
                    ], 400);
                }
                // اعتبارسنجی فرمت تاریخ شمسی
                if (!preg_match('/^(\d{4})\/(\d{1,2})\/(\d{1,2})$/', $fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'فرمت تاریخ صحیح نیست (مثال: ۱۴۰۰/۰۱/۰۱)'
                    ], 400);
                }
                break;

            case 'nationalcode':
                if (strlen($fieldValue) !== 10 || !is_numeric($fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'کد ملی باید 10 رقم باشد'
                    ], 400);
                }
                break;

            case 'phone':
            case 'father_phone':
            case 'mother_phone':
                if (strlen($fieldValue) !== 11 || !preg_match('/^09[0-9]{9}$/', $fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'شماره موبایل باید 11 رقم و با 09 شروع شود'
                    ], 400);
                }
                break;

            case 'telephone':
            case 'school_telephone':
                if (!empty($fieldValue) && (strlen($fieldValue) !== 11 || !is_numeric($fieldValue))) {
                    return response()->json([
                        'success' => false,
                        'message' => 'تلفن ثابت باید 11 رقم باشد'
                    ], 400);
                }
                break;

            case 'last_score':
                if (!is_numeric($fieldValue) || $fieldValue < 0 || $fieldValue > 20) {
                    return response()->json([
                        'success' => false,
                        'message' => 'معدل باید بین 0 تا 20 باشد'
                    ], 400);
                }
                break;

            case 'english_proficiency':
                if (!is_numeric($fieldValue) || $fieldValue < 0 || $fieldValue > 100) {
                    return response()->json([
                        'success' => false,
                        'message' => 'سطح انگلیسی باید بین 0 تا 100 باشد'
                    ], 400);
                }
                break;

            case 'siblings_count':
            case 'siblings_rank':
                if (!is_numeric($fieldValue) || $fieldValue < 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'مقدار باید عدد مثبت باشد'
                    ], 400);
                }
                break;

            case 'grade':
                if (empty($fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'پایه تحصیلی الزامی است'
                    ], 400);
                }
                break;

            case 'school':
            case 'principal':
                if (empty($fieldValue) || strlen(trim($fieldValue)) < 2) {
                    return response()->json([
                        'success' => false,
                        'message' => 'این فیلد باید حداقل 2 کاراکتر باشد'
                    ], 400);
                }
                break;

            case 'rental':
                if ($fieldValue !== '0' && $fieldValue !== '1') {
                    return response()->json([
                        'success' => false,
                        'message' => 'وضعیت مسکن نامعتبر است'
                    ], 400);
                }
                break;

            case 'address':
                if (empty($fieldValue) || strlen(trim($fieldValue)) < 10) {
                    return response()->json([
                        'success' => false,
                        'message' => 'آدرس باید حداقل 10 کاراکتر باشد'
                    ], 400);
                }
                break;

            case 'know':
            case 'counseling_method':
                if (empty($fieldValue)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'انتخاب این گزینه الزامی است'
                    ], 400);
                }
                break;


        }

        // بروزرسانی فیلد
        $requestModel->{$request->field_name} = $fieldValue;
        $requestModel->update();

        return response()->json([
            'success' => true,
            'message' => 'فیلد با موفقیت بروزرسانی شد',
            'new_value' => $fieldValue
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در بروزرسانی: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * دریافت اطلاعات جدید درخواست برای بروزرسانی modal
 */
public function getRequestData($id)
{
    try {
        $requestModel = ModelRequest::with(['user', 'major'])->findOrFail($id);

        // بررسی اجازه دسترسی
        $user = Auth::user();
        if ($user->role === 'user' && $requestModel->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'شما اجازه دسترسی به این درخواست را ندارید'
            ], 403);
        }

        // آماده‌سازی اطلاعات برای فرستادن
        $imgUrl = $requestModel->imgpath ? route('img', ['filename' => $requestModel->imgpath]) : null;
        $gradeSheetUrl = $requestModel->gradesheetpath ? route('img', ['filename' => $requestModel->gradesheetpath]) : null;

        // Debug log
        \Log::info('Generated URLs - Image: ' . $imgUrl . ', GradeSheet: ' . $gradeSheetUrl);

        $requestData = [
            'id' => $requestModel->id,
            'name' => $requestModel->name,
            'birthdate' =>  Jalalian::fromDateTime($requestModel->birthdate)->format(' Y/m/d ') ,
            'nationalcode' => $requestModel->nationalcode,
            'phone' => $requestModel->phone,
            'telephone' => $requestModel->telephone,
            'grade' => $requestModel->grade,
            'school' => $requestModel->school,
            'last_score' => $requestModel->last_score,
            'principal' => $requestModel->principal,
            'school_telephone' => $requestModel->school_telephone,
            'major_name' => $requestModel->major ? $requestModel->major->name : null,
            'english_proficiency' => $requestModel->english_proficiency,
            'rental' => $requestModel->rental,
            'address' => $requestModel->address,
            'siblings_count' => $requestModel->siblings_count,
            'siblings_rank' => $requestModel->siblings_rank,
            'know' => $requestModel->know,
            'counseling_method' => $requestModel->counseling_method,
            'why_counseling_method' => $requestModel->why_counseling_method,
            'father_name' => $requestModel->father_name,
            'father_phone' => $requestModel->father_phone,
            'father_job' => $requestModel->father_job,
            'father_income' => $requestModel->father_income,
            'father_job_address' => $requestModel->father_job_address,
            'mother_name' => $requestModel->mother_name,
            'mother_phone' => $requestModel->mother_phone,
            'mother_job' => $requestModel->mother_job,
            'mother_income' => $requestModel->mother_income,
            'mother_job_address' => $requestModel->mother_job_address,
            'motivation' => $requestModel->motivation,
            'spend' => $requestModel->spend,
            'how_am_i' => $requestModel->how_am_i,
            'favorite_major' => $requestModel->favorite_major,
            'future' => $requestModel->future,
            'help_others' => $requestModel->help_others,
            'suggestion' => $requestModel->suggestion,
            'imgpath' => $requestModel->imgpath,
            'imgpath_url' => $requestModel->imgpath ? route('img', ['filename' => $requestModel->imgpath]) : null,
            'gradesheetpath' => $requestModel->gradesheetpath,
            'gradesheetpath_url' => $requestModel->gradesheetpath ? route('img', ['filename' => $requestModel->gradesheetpath]) : null,
            'story' => $requestModel->story,
            'created_at' => $requestModel->created_at,
            'updated_at' => $requestModel->updated_at,
        ];

        return response()->json([
            'success' => true,
            'request' => $requestData
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'خطا در دریافت اطلاعات: ' . $e->getMessage()
        ], 500);
    }
}
}
