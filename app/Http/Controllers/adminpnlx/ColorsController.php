<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Colors;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class ColorsController extends BaseController {

	public $model		=	'Colors';
	public $sectionName	=	'Colors';
	public $headingName	=	'Colors';
	public $sectionNameSingular	=	'Color';
	public $sectionNamePlural	='Colors';
	public function __construct(Request $request) {
		parent::__construct();
		View::share('modelName',$this->model);
		View::share('sectionName',$this->sectionName);
		View::share('headingName',$this->headingName);
		View::share('sectionNameSingular',$this->sectionNameSingular);
		View::share('sectionNamePlural',$this->sectionNamePlural);
		$this->request = $request;
	}
	 
	/**
	* Function for display all Links
	*
	* @param null
	*
	* @return view page. 
	*/
	public function index(Request $request){
	  	$DB					=	Colors::query();
		$searchVariable		=	array(); 
		$inputGet			=	$request->all();
		if (($request->all())) {
			$searchData			=	$request->all();
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
				if($fieldValue != ""){
					if($fieldName == "name"){
						$DB->where("colors.name",'like','%'.$fieldValue.'%');
					}/* elseif($fieldName == "link"){
						$DB->where("Colors.value",'like','%'.$fieldValue.'%');
					} */
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("colors.*");
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		$results = $model->orderBy($sortBy, $order)->paginate(Config::get("Reading.records_per_page"));
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($request->all())->render();   
		//dd($results);
		return  View::make("admin.$this->sectionName.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()


	public function edit($modelId = 0){
		$model				=	Colors::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		return  View::make("admin.$this->sectionName.edit",array('model' => $model));
	} // end edit()
	
	
	/**
	* Function for update Link module 
	*
	* @param $modelId as id of Link module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	Colors::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	  
		$validator 					=	Validator::make(
			$request->all(),
			array(
				'name'			   => "required",
				'color' 			   => 'required',
			),
			array(
				"name.required"		=>	trans("The name field is required."),
				"color.required"		=>	trans("The color field is required."),
				
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->name		 		= 	$thisData['name'];
			$obj->value		 		= 	$thisData['color'];
			$objSave				=   $obj->save();
			$last_id				=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->sectionName.".index");
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been updated successfully"));
			return Redirect::route($this->sectionName.".index");
		}
	}// end update()

	
}// end Class