<?php 
namespace App\Exports;

use App\Models\Contact;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ContactExport implements FromView,WithColumnWidths
{	

	public function __construct($group_id){
		$this->group_id = $group_id;
	}

    public function view() : View
    {	
        $contactData = [];

        $data = Contact::NotDeleted()->where('group_id',$this->group_id)->get();
        foreach ($data as $key => $value) {
            $contactData[$key] = new \stdClass();
            $contactData[$key]->id = $value->id;
            $contactData[$key]->group = $value->Group != null ? $value->Group->{'name_'.(\Session::has('group_id') ? LANGUAGE_PREF : 'ar')} : '';
            $contactData[$key]->phone2 = str_replace('+', '', str_replace('@c.us','',$value->phone));
            $contactData[$key]->name = $value->name;
            $contactData[$key]->email = $value->email;
            $contactData[$key]->country = $value->country;
            $contactData[$key]->city = $value->city;
        }
    	return view('Tenancy.Contact.Views.smallTable', [
            'data' => $contactData,
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 55,            
            'C' => 55,            
            'D' => 55,            
            'E' => 55,            
            'F' => 55,            
            'G' => 55,            
        ];
    }
}