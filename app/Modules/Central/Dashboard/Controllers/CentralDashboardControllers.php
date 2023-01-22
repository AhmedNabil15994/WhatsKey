<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\CentralGroup;
use App\Models\CentralUser;

class CentralDashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard()
    {   
        $input = \Request::all();
    
        Session::forget('check_user_id');
        $now = date('Y-m-d');
        $start = $now;
        $end = $now;
        $date = null;
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $start = $input['from'].' 00:00:00';
            $end = $input['to'].' 23:59:59';
            $date = 1;
        }
        // $contactUs = ContactUs::getByDate($date,$start,$end);

        $data['contactUs'] = [];//$contactUs['data'];
        $data['contactUsCount'] = 0;//$contactUs['count'];
        $data['webActions'] =  [];
        $data['webActionsCount'] =  0;
        // $data['chartData1'] = [];//$this->getChartData($start,$end,'\ContactUs');
        // $data['chartData2'] = $this->getChartData('\CentralUser',$start,$end);
        $data['addCount'] = 0;
        $data['editCount'] = 0;
        $data['deleteCount'] = 0;
        $data['fastEditCount'] = 0;
        return view('Central.Dashboard.Views.dashboard')->with('data',(object) $data);
    }

    public function changeChannel(Request $request){
        if($request->ajax()){
            $userObj = User::getData(User::getOne(USER_ID));
            if(!Session::has('channel')){
                if(in_array($request->channel, $userObj->channelIDS)){
                    Session::put('channel', $request->channel);
                }
            }else{
                Session::forget('channel');
                if(in_array($request->channel, $userObj->channelIDS)){
                    Session::put('channel', $request->channel);
                }
            } 
            return Redirect::back();
        }
    }

    public function changeTheme(Request $request){
        if($request->ajax()){
            $type = $request->type;
            $value = $request->value;
            $dataObj = UserTheme::where('user_id',USER_ID)->first();
            if(!$dataObj){
                $dataObj = new UserTheme;
            }
            $dataObj->user_id = USER_ID;
            $dataObj->$type = $value;
            $dataObj->save();
            return Redirect::back();
        }
    }

    public function changeThemeToDefault(Request $request){
        if($request->ajax()){
            $type = $request->type;
            $value = $request->value;
            $dataObj = UserTheme::where('user_id',USER_ID)->first();
            $dataObj->theme = 'light';
            $dataObj->width = 'fluid';
            $dataObj->menus_position = 'fixed';
            $dataObj->sidebar_size = 'default';
            $dataObj->user_info = 'false';
            $dataObj->top_bar = 'dark';
            $dataObj->save();
            return Redirect::back();
        }
    }

}
