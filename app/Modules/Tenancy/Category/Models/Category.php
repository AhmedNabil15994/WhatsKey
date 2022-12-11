<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Category extends Model{

    use \TraitsFunc;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = ['id','channel','name_ar','name_en','color_id','labelId','status','created_by','created_at'];    
    public $timestamps = false;

    static function getOne($id){
        return self::NotDeleted()
            ->where('id', $id)
            ->first();
    }

    static function dataList($ids=null,$labelIds=null) {
        $input = \Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                    if (isset($input['color_id']) && !empty($input['color_id'])) {
                        $query->where('color_id',$input['color_id']);
                    }
                    if (isset($input['name_ar']) && !empty($input['name_ar'])) {
                        $query->where('name_ar', 'LIKE', '%' . $input['name_ar'] . '%');
                    }
                    if (isset($input['name_en']) && !empty($input['name_en'])) {
                        $query->where('name_en', 'LIKE', '%' . $input['name_en'] . '%');
                    }
                    if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                        $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
                    }
                });
        if(isset($input['channel']) && !empty($input['channel'])){
            $source->where('channel',$input['channel']);
        }

        if($ids != null){
            $source->whereIn('id',$ids);
        }

        if($labelIds != null){
            $source->where('labelId','!=','')->whereIn('labelId',$labelIds);
        }

        $source->orderBy('sort','ASC');
        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $extraData = self::getColorData($source->color_id);
        $data->channel = $source->channel;
        $data->labelClass = 'border-0 text-white border-radius-0 w-100 label label-'.$source->color_id;
        $data->name_ar = $source->name_ar;
        $data->name_en = $source->name_en;
        $data->labelId = $source->labelId;
        $data->color_id = $source->color_id;
        $data->color = $extraData['color'];
        $data->colorName = $extraData['title'];
        $data->title = \Session::has('group_id') ? $source->{'name_'.LANGUAGE_PREF} : $source->name_ar;
        $data->whatsappName = $source->name_ar;
        $data->status = $source->status;
        $data->sort = $source->sort;
        $data->created_at = \Helper::formatDate($source->created_at);
        return $data;
    }

    static function getColors(){
        return [
            ['id' => 0,'color' => '#A52C71' , 'title' => 'LavenderRose',],
            ['id' => 1,'color' => '#8FA840' , 'title' => 'Galliano',],
            ['id' => 2,'color' => '#C1A03F' , 'title' => 'MoodyBlue',],
            ['id' => 3,'color' => '#772237' , 'title' => 'Manz',],
            ['id' => 4,'color' => '#AC8671' , 'title' => 'DarkTurquoise',],

            ['id' => 5,'color' => '#EFB32F' , 'title' => 'Pink',],
            ['id' => 6,'color' => '#B4B227' , 'title' => 'Chinook',],
            ['id' => 7,'color' => '#C79DCD' , 'title' => 'SunsetOrange',],
            ['id' => 8,'color' => '#8B6890' , 'title' => 'DeepSkyBlue',],
            ['id' => 9,'color' => '#FF898D' , 'title' => 'InchWorm',],

            ['id' => 10,'color' => '#54C166' , 'title' => 'Orange',],
            ['id' => 11,'color' => '#FF7B6C' , 'title' => 'ColumbiaBlue',],
            ['id' => 12,'color' => '#28C4DB' , 'title' => 'Amethyst',],
            ['id' => 13,'color' => '#56C9FF' , 'title' => 'MonaLisa',],
            ['id' => 14,'color' => '#72666A' , 'title' => 'MayaBlue',],

            ['id' => 15,'color' => '#7D8FA5' , 'title' => 'Sunglow',],
            ['id' => 16,'color' => '#5796FF' , 'title' => 'Lavender',],
            ['id' => 17,'color' => '#6C267E' , 'title' => 'Nepal',],
            ['id' => 18,'color' => '#7ACCA4' , 'title' => 'MediumAquamarine',],
            ['id' => 19,'color' => '#23353F' , 'title' => 'Dark',],
        ];
    }

    static function getColorData($colorId){
       $data = self::getColors();
       foreach ($data as $key => $value) {
           if($value['id'] == $colorId){
                return $value;
           }
       }
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

    static function newCategory($labelObj){
        $labelObj = (object) $labelObj;
        $contactObj = self::where('labelId',$labelObj->id)->first();
        if($contactObj == null){
            $contactObj = new self;
            $contactObj->name_ar = $labelObj->title;
            $contactObj->name_en = $labelObj->title;
            $contactObj->labelId = $labelObj->id;
            $contactObj->color_id = $labelObj->color;
            $contactObj->sort = self::newSortIndex();
            $contactObj->created_at = date('Y-m-d H:i:s');
        }
        $contactObj->status = 1;
        $contactObj->save();
    }
}
