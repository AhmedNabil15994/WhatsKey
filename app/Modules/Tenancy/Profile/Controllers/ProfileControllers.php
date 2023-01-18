<?php namespace App\Http\Controllers;

use App\Models\CentralUser;
use App\Models\PaymentInfo;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Storage;
use Validator;

class ProfileControllers extends Controller
{

    use \TraitsFunc;

    public function personalInfo()
    {
        $userObj = User::authenticatedUser();
        $data['designElems']['mainData'] = [
            'title' => trans('main.account_setting'),
            'icon' => 'fa fa-user',
        ];
        $data['data'] = $userObj;
        $data['paymentInfo'] = $userObj->paymentInfo;
        return view('Tenancy.Profile.Views.personalInfo')->with('data', (object) $data);
    }

    public function updatePersonalInfo()
    {
        $input = \Request::all();
        $mainUserObj = User::getOne(USER_ID);
        $dataObj = User::getData($mainUserObj);
        $domainObj = \DB::connection('main')->table('domains')->where('domain', $dataObj->domain)->first();

        $oldDomainValue = $mainUserObj->domain;

        if (isset($input['email']) && !empty($input['email'])) {
            $userObj = User::checkUserBy('email', $input['email'], USER_ID);
            $oldEmail = null;
            if ($userObj) {
                if ($userObj->deleted_by != null) {
                    $oldEmail = $userObj->email;
                    User::where('email', $input['email'])->where('deleted_by', '!=', null)->delete();
                } else {
                    Session::flash('error', trans('main.emailError'));
                    return redirect()->back()->withInput();
                }
            }
            $mainUserObj->email = $input['email'];

            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['email' => $input['email']]);
            }
        }

        if (isset($input['phone']) && !empty($input['phone'])) {
            $userObj = User::checkUserBy('phone', $input['phone'], USER_ID);
            $oldPhone = null;
            if ($userObj) {
                if ($userObj->deleted_by != null) {
                    $oldPhone = $userObj->phone;
                    User::where('phone', $input['phone'])->where('deleted_by', '!=', null)->delete();
                } else {
                    Session::flash('error', trans('main.phoneError'));
                    return redirect()->back()->withInput();
                }
            }
            $mainUserObj->phone = $input['phone'];

            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'phone' => $input['phone'],
            ]);
            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['phone' => $input['phone']]);
            }
        }

        if (isset($input['two_auth']) && !empty($input['two_auth'])) {
            $mainUserObj->two_auth = $input['two_auth'];
        }

        if (isset($input['emergency_number']) && !empty($input['emergency_number'])) {
            $mainUserObj->emergency_number = $input['emergency_number'];
        }

        if (isset($input['domain']) && !empty($input['domain']) && $oldDomainValue != $input['domain']) {

            $rules = [
                'domain' => 'regex:/^([a-zA-Z0-9][a-zA-Z0-9-_])*[a-zA-Z0-9]*[a-zA-Z0-9-_]*[[a-zA-Z0-9]$/',
            ];

            $message = [
                'domain.regex' => trans('main.domain2Validate'),
            ];

            $validate = \Validator::make($input, $rules, $message);
            if ($validate->fails()) {
                Session::flash('error', $validate->messages()->first());
                return redirect()->back()->withInput();
            }

            $checkDomainObj = \DB::connection('main')->table('domains')->where('domain', $input['domain'])->first();
            if ($checkDomainObj && $checkDomainObj->domain != $dataObj->domain) {
                Session::flash('error', trans('main.domainValidate2'));
                return redirect()->back()->withInput();
            }

            $mainUserObj->domain = $input['domain'];
            \DB::connection('main')->table('domains')->where('tenant_id', $domainObj->tenant_id)->limit(1)->update([
                'domain' => $input['domain'],
            ]);
            // // Update User With Settings For Whatsapp Based On His Domain
            $mainWhatsLoopObj = new \OfficialHelper();
            $myData = [
                'sendDelay' => '0',
                'webhooks' => [
                    'messageNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/messages-webhook',
                    'ackNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/acks-webhook',
                    'chatNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/chats-webhook',
                    'businessNotifications' => str_replace('://', '://'.$input['domain'].'.', config('app.BASE_URL')).'/services/webhooks/business-webhook',
                ],
                'ignoreOldMessages' => 1,
            ];
            $updateResult = $mainWhatsLoopObj->updateChannelSetting($myData);
            $result = $updateResult->json();
        }

        if (isset($input['company']) && !empty($input['company'])) {
            $mainUserObj->company = $input['company'];
            CentralUser::where('id', User::first()->id)->update(['company' => $input['company']]);
        }

        if (isset($input['name']) && !empty($input['name'])) {
            $mainUserObj->name = $input['name'];
            if ($mainUserObj->group_id == 1) {
                CentralUser::where('id', User::first()->id)->update(['name' => $input['name']]);
            }
            \DB::connection('main')->table('tenants')->where('id', $domainObj->tenant_id)->update([
                'title' => $input['name'],
            ]);
        }

        $mainUserObj->save();

        $photos_name = Session::get('photos');
        if ($photos_name) {
            $photos = Storage::files($photos_name);
            if (count($photos) > 0) {
                $images = self::addImage($photos[0], $mainUserObj->id);
                if ($images == false) {
                    Session::flash('error', trans('main.uploadProb'));
                    return redirect()->back()->withInput();
                }
                $mainUserObj->image = $images;
                $mainUserObj->save();
            }
        }

        if ($input['domain'] != $oldDomainValue) {
            return redirect()->to(config('tenancy.protocol') . $input['domain'] . '.' . config('tenancy.central_domains')[0] . '/login');
        }

        Session::forget('photos');
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function postChangePassword()
    {
        $input = \Request::all();
        $rules = [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ];

        $message = [
            'password.required' => trans('auth.passwordValidation'),
            'password.confirmed' => trans('auth.passwordValidation2'),
            'password_confirmation.required' => trans('auth.passwordValidation3'),
        ];

        $validate = Validator::make($input, $rules, $message);
        if ($validate->fails()) {
            Session::flash('error', $validate->messages()->first());
            return back()->withInput();
        }

        $password = $input['password'];
        $userObj = User::NotDeleted()->find(USER_ID);
        if ($userObj == null) {
            Session::flash('error', trans('auth.invalidUser'));
            return back()->withInput();
        }
        $userObj->password = Hash::make($password);
        $userObj->save();

        if ($userObj->group_id == 1) {
            CentralUser::where('id', $userObj->id)->update(['password' => Hash::make($password)]);
        }

        Session::flash('success', trans('auth.passwordChanged'));
        return \Redirect::back()->withInput();
    }

    public function postPaymentInfo(Request $request)
    {
        $input = \Request::all();
        $rules = [
            'address' => 'required',
        ];

        $message = [
            'address.required' => trans('main.addressValidation'),
        ];

        $validate = Validator::make($input, $rules, $message);
        if ($validate->fails() && !$request->ajax()) {
            Session::flash('error', $validate->messages()->first());
            return back()->withInput();
        }

        $userObj = User::authenticatedUser();
        if ($userObj->paymentInfo) {
            $paymentInfoObj = $userObj->paymentInfo;
            $paymentInfoObj->updated_at = DATE_TIME;
            $paymentInfoObj->updated_by = USER_ID;
            $type = 2;
        } else {
            $paymentInfoObj = new PaymentInfo();
            $paymentInfoObj->created_at = DATE_TIME;
            $paymentInfoObj->created_by = USER_ID;
            $type = 1;
        }

        $paymentInfoObj->user_id = USER_ID;
        $paymentInfoObj->address = $input['address'];
        $paymentInfoObj->address2 = $input['address2'];
        $paymentInfoObj->city = $input['city'];
        if(isset($input['payment_method'])){
            $paymentInfoObj->payment_method = $input['payment_method'];
        }
        if(isset($input['currency'])){
            $paymentInfoObj->currency = $input['currency'];
        }
        $paymentInfoObj->region = $input['region'];
        $paymentInfoObj->country = $input['country'];
        $paymentInfoObj->postal_code = $input['postal_code'];
        $paymentInfoObj->tax_id = $input['tax_id'];
        $paymentInfoObj->save();

        if($request->ajax()){
            return \TraitsFunc::SuccessResponse(trans('main.editSuccess'));
        }
        Session::flash('success', trans('main.editSuccess'));
        return \Redirect::back()->withInput();
    }

    public function uploadImage(Request $request, $id = false)
    {
        $rand = rand() . date("YmdhisA");
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            Storage::put($rand, $files);
            Session::put('photos', $rand);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images, $nextID = false)
    {
        $fileName = \ImagesHelper::UploadFile('users', $images, $nextID);
        if ($fileName == false) {
            return false;
        }
        return $fileName;
    }

    public function deleteImage()
    {
        $id = (int) USER_ID;
        $input = \Request::all();

        $menuObj = User::find($id);
        if ($menuObj == null) {
            return \TraitsFunc::ErrorMessage(trans('main.userNotFound'));
        }

        \ImagesHelper::deleteDirectory(public_path('/') . '/uploads/users/' . $id . '/' . $menuObj->image);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse(trans('main.imgDeleted'));
    }
}
