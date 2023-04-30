<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Cms;
use Illuminate\Http\Request;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;
// use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,mongoDate,Redirect,Response,Session,URL,View,Validator;

/** 
* CmspagesController Controller
*
* Add your methods in the class below
*
*/
class CmspagesController extends BaseController {

	public $model				=	'Cms';
	public $sectionName			=	'Cms Pages';
	public $sectionNameSingular	=	'Cms Page';
	
	public function __construct(Request $request) {
		parent::__construct();
		View::share('modelName',$this->model);
		View::share('sectionName',$this->sectionName);
		View::share('sectionNameSingular',$this->sectionNameSingular);
		$this->request = $request;
	}
	 
	// Default function searching listing
	public function index(Request $request){  
		$DB					=	Cms::query();
		$searchVariable		=	array(); 
		$inputGet			=	$request->all();
		if ($request->all()) {
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
					if($fieldName == "page_name"){
						$DB->where("cms_pages.page_name",'like','%'.$fieldValue.'%');
					}
					if($fieldName == "title"){
						$DB->where("cms_pages.title",'like','%'.$fieldValue.'%');
					}
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$sortBy = ($request->input('sortBy')) ? $request->input('sortBy') : 'created_at';
	    $order  = ($request->input('order')) ? $request->input('order')   : 'DESC';
		
		$records_per_page	=	($request->input('per_page')) ? $request->input('per_page') : Config::get("Reading.records_per_page");
		$results = $DB->orderBy($sortBy, $order)->paginate($records_per_page);
		$complete_string		=	$request->query();
		unset($complete_string["sortBy"]);
		unset($complete_string["order"]);
		$query_string			=	http_build_query($complete_string);
		$results->appends($inputGet)->render();
		return  View::make("admin.$this->model.index",compact('results','searchVariable','sortBy','order','query_string'));
	}// end index()

	// Cms page view 
	public function view($modelId = 0){
		$model	=	Cms::findorFail($modelId);
		return  View::make("admin.$this->model.view",compact('model'));
	}

	// Cms page edit
	public function edit($modelId = 0,Request $request){
		$model	=	Cms::findorFail($modelId);
		
		return  View::make("admin.$this->model.edit",array('model' => $model));
	}

	// Update the cms tables
	function update($modelId,Request $request){
		$model	=	Cms::findorFail($modelId);
		
		///$this->request->replace($this->arrayStripTags($request->all()));
		$thisData					=	$request->all();
		$validator = Validator::make(
			array(
				'page_name' 		=> $request->input('page_name'),
				'title' 			=> $request->input('page_title'),
				'body' 				=> $request->input('body'),
			),
			array(
				'page_name' 		=> 'required',
				'title' 			=> 'required',
				'body' 				=> 'required',
			),
			array(
				"page_name.required"		=>	trans("The page name field is required."),
				"title.required"			=>	trans("The page title field is required."),				
				"body.required"				=>	trans("The description field is required."),				
			)
		);
		
		if ($validator->fails()){
			return Redirect::back()->withErrors($validator)->withInput();
		}else{ 
			$obj 					= $model;
			$obj->page_name    		= $request->input('page_name');
			$obj->title   			= $request->input('page_title');
			$obj->body   			= $request->input('body');
			$obj->save();
			
			$lastId					=	$obj->id;
			Session::flash('success',trans($this->sectionNameSingular." has been updated successfully"));
			return Redirect::route($this->model.".index");
		}
	}


}// end CmspagesController
