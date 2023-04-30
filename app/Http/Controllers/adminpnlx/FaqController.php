<?php
namespace App\Http\Controllers\adminpnlx;
use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Faq;
use Illuminate\Http\Request;
use App, Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Response,Session,URL,View,Validator;

/**
*  LinkssController Controller
*
* Add your methods in the class below
*
*/
class FaqController extends BaseController {

	public $model		=	'Faq';
	public $sectionName	=	'Faq';
	public $headingName	=	'Faq';
	public $sectionNameSingular	=	'Faq';
	public $sectionNamePlural	='Faq';
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
	  	$DB					=	Faq::query();
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
					if($fieldName == "question"){
						$DB->where("faq.question",'like','%'.$fieldValue.'%');
					}/* elseif($fieldName == "link"){
						$DB->where("reviews.value",'like','%'.$fieldValue.'%');
					} */
				}
				$searchVariable	=	array_merge($searchVariable,array($fieldName => $fieldValue));
			}
		}
		$model 					= 	$DB->select("faq.*")->where('is_deleted',0);
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
	
	
	
	/**
	* Function for add new Review
	*
	* @param null
	*
	* @return view page. 
	*/
	public function add(){
		return  View::make("admin.$this->sectionName.add");
	}// end add()
	
	
	
	/**
	* Function for save Review module 
	*
	* @param $modelId as id of Link module 
	*
	* @return redirect page. 
	*/
	function save(Request $request){
		
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	   // dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
				'question'				    => 'required',
				'answer' 					=> 'required',
			),
			array(
				"question.required"			    =>	trans("The question field is required."),
				"answer.required"		=>	trans("The answer is required."),
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=    new Faq;
			$obj->question		 	= 	$thisData['question'];
			$obj->answer		 	= 	$thisData['answer'];
			$objSave				=   $obj->save();
			$last_id				=	$obj->id;
			if(!$objSave) {
				DB::rollback();
				Session::flash('error', trans("Something went wrong.")); 
				return Redirect::route($this->sectionName.".index");
			}
			DB::commit();
			Session::flash('success',trans($this->sectionNameSingular." has been added successfully"));
			return Redirect::route($this->sectionName.".index");
		}
	}// end update()


	public function edit($modelId = 0){
		$model				=	Faq::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		return  View::make("admin.$this->sectionName.edit",array('model' => $model));
	} // end edit()
	
	
	/**
	* Function for update Review module 
	*
	* @param $modelId as id of Review module 
	*
	* @return redirect page. 
	*/
	function update($modelId = 0,Request $request){
		$model							=	Faq::find($modelId);
		if(empty($model)) {
			return Redirect::route($this->sectionName.".index");
		}
		$this->request->replace($this->arrayStripTags($request->all()));
		$thisData						=	$request->all();
	    //dd($thisData);

		$validator 					=	Validator::make(
			$request->all(),
			array(
				'question'				    => 'required',
				'answer' 					=> 'required',
			),
			array(
				"question.required"		  =>	trans("The question field is required."),
				"answer.required"		  =>	trans("The answer is required."),
			)
		);
	
		if ($validator->fails()) {	
		//s	dd($validator);
			return Redirect::back()->withErrors($validator)->withInput();
		}else{
			DB::beginTransaction();
			$obj 					=   $model;
			$obj->question		 	= 	$thisData['question'];
			$obj->answer		 	= 	$thisData['answer'];
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
	
	/**
	* Function for Delete Review  
	*
	* @param $modelId as id of Review 
	* @param $modelStatus as status of Review 
	*
	* @return redirect page. 
	*/	
	public function delete($reviewId = 0){
		$reviewDetails	=	Faq::find($reviewId);
		//dd($reviewDetails);
		if(empty($reviewDetails)) {
			return Redirect::route($this->model.".index");
		}
		if($reviewId){		
			Faq::where('id',$reviewId)->update(array('is_deleted'=>1));
			Session::flash('flash_notice',trans($this->sectionNameSingular." has been removed successfully")); 
			return Redirect::route($this->sectionName.".index");
		}
		return Redirect::back();
	}// end delete()

	
}// end Class