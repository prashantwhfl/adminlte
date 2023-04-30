<?php
/**
* Settings Controller
*
* Add your methods in the class below
*
* This file will render views from views/settings
*/
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use mjanssen\BreadcrumbsBundle\Breadcrumbs as Breadcrumb;
use Illuminate\Http\Request;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Response,Session,URL,View,Validator;
use App\Models\Setting;
class SettingsController extends BaseController {
	
	public function __construct(Request $request){
		$this->request = $request;
	}
	
/**
* function for list all settings
*
* @param  null
* 
* @return view page
*/
	public function listSetting(Request $request){	
		$DB				=	Setting::query();
		$searchVariable	=	array(); 
		$inputGet		=	$request->all();
		if ($inputGet) {
			$searchData	=	$request->all();
			unset($searchData['display']);
			unset($searchData['_token']);
			if(isset($searchData['order'])){
				unset($searchData['order']);
			}
			if(isset($searchData['sortBy'])){
				unset($searchData['sortBy']);
			}

			if(isset($searchData['page'])){
				unset($searchData['page']);
			}
			foreach($searchData as $fieldName => $fieldValue){
				if(!empty($fieldValue)){
					$DB->where("$fieldName",'like','%'.$fieldValue.'%');
					$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
				}
			}
		}
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'id';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'ASC';
		$result = $DB->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		
		return  View::make('admin.settings.index',compact('result','searchVariable','sortBy','order'));
	} // end listSetting()
/**
* prefix function
*
* @param $prefix as prefix
* 
* @return void
*/
	public function prefix($prefix = null,Request $request) {
		//$activeLanguage = 	DB::table("languages")->where("is_active",1)->pluck("title","lang_code")->toArray();
		$result = Setting::where('key', 'like', $prefix.'%')->orderBy('id', 'ASC')->get()->toArray();
		return  View::make('admin.settings.prefix', compact('result','prefix'));
	}// end prefix()
/**
* update prefix function
*
* @param $prefix as prefix
* 
* @return void
*/
	public function updatePrefix($prefix = null,Request $request){
		$allData				=	$request->all();
		$thisData				=	$request->all(); 
		$this->request->replace($this->arrayStripTags($request->all()));
		$allData				=	$request->all(); 
		/* echo "<pre>";
		print_r($allData);die; */
		if(!empty($allData)){
			if(!empty($allData['Setting'])){
				foreach($allData['Setting'] as $key => $value){
					if(!empty($value["'id'"]) && !empty($value["'key'"])){
						
						if($value["'type'"] == 'checkbox'){
							$val	=	(isset($value["'value'"])) ? 1 : 0;
						}else{
							$val	=	(isset($value["'value'"])) ? $value["'value'"] : '';
						}
						
						Setting::where('id', $value["'id'"])->update(array(
							'key'   	 		=>  $value["'key'"],
							'value' 			=>  $val
						)); 
					}
				}
			}
		}
		$this->settingFileWrite();
		Session::flash('flash_notice', 'Settings updated successfully.'); 
		return  Redirect::intended('adminpnlx/settings/prefix/'.$prefix);
	}//updatePrefix()
/**
* function add new settings view page
*
*@param null
* @return void
*/
	public function addSetting(Request $request){
		return  View::make('admin.settings.add');
	}//end addSetting()
/**
* function for save added new settings
*
*@param null
*
* @return void
*/
	public function saveSetting(Request $request){

		$thisData				=	$request->all(); 
		$this->request->replace($this->arrayStripTags($request->all()));
		$validator  = 	Validator::make(
			$request->all(),
			array(
				'title' 		=> 'required',
				'key' 			=> 'required',
				'value' 		=> 'required',
				'input_type' 	=> 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('adminpnlx/settings/add-setting')
				->withErrors($validator)->withInput();
		}else{
			
			$obj	 = new Setting;

			$obj->title    			= $request->input('title');
			$obj->key   			= $request->input('key');
			$obj->value   			= $request->input('value');
			$obj->input_type   		= $request->input('input_type');
			$obj->editable  		= 1;
			
			$obj->save();
		}	
		$this->settingFileWrite();	
		Session::flash('flash_notice', 'Setting added successfully.'); 
		return Redirect::intended('adminpnlx/settings');
	}//end saveSetting()
/**
* function edit settings view page
*
*@param $Id as Id
*
* @return void
*/
	public function editSetting($Id,Request $request){
		$result			 = 	Setting::find($Id);
		if(empty($result)) {
			return Redirect::to('adminpnlx/dashboard');
		}
		return  View::make('admin.settings.edit',compact('result'));
	}//end editSetting()
/**
* function for update setting
*
* @param $Id as Id
*
* @return void
*/
	public function updateSetting($Id,Request $request){
		$thisData				=	$request->all(); 
		$this->request->replace($this->arrayStripTags($request->all()));
		$validator  			= 	Validator::make(
			$request->all(),
			array(
				'title' 		=> 'required',
				'key' 			=> 'required',
				'value' 		=> 'required',
				'input_type' 	=> 'required'
			)
		);
		if ($validator->fails())
		{	
			return Redirect::to('adminpnlx/settings/edit-setting/'.$Id)
				->withErrors($validator)->withInput();
		}else{
			$obj	 				=  Setting::find($Id);
			$obj->title    			= $request->input('title');
			$obj->key   			= $request->input('key');
			$obj->value   			= $request->input('value');
			$obj->input_type   		= $request->input('input_type');
			$obj->editable  		= 1;
			$obj->save();
		}	
		$this->settingFileWrite();	
		Session::flash('flash_notice', 'Setting updated successfully.'); 
		return Redirect::intended('adminpnlx/settings');
	}//end updateSetting()
/**
* function for delete setting
*
* @param $Id as Id
*
* @return void
*/
	public function deleteSetting($Id = 0,Request $request){
		if($Id){
			/* $obj	=  Setting::find($Id);
			$obj->delete(); */
			$this->_delete_table_entry('settings',$Id,'id');
			Session::flash('flash_notice', 'Setting deleted successfully.'); 
		}
		$this->settingFileWrite();
		return Redirect::intended('adminpnlx/settings');
	}//end deleteSetting()
/**
* function for write file on update and create
*
*@param $Id as Id
*
* @return void
*/	
	public function settingFileWrite() {
		$DB		=	Setting::query();
		$list	=	$DB->orderBy('key','ASC')->get(array('key','value'))->toArray();
		
        $file = SETTING_FILE_PATH;
		$settingfile = '<?php ' . "\n";
		
		$append_string		=	"";
		foreach($list as $value){
			$val		  =	 str_replace('"',"'",$value['value']);
			$settingfile .=  'config::set("'.$value['key'].'", "'.$val.'");' . "\n"; 
		}
		$bytes_written = File::put($file, $settingfile);
		
		if ($bytes_written === false)
		{
			die("Error writing to file");
		}
	}//end settingFileWrite()
}//end SettingsController class
