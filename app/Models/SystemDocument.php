<?php 
namespace App\Model; 
use Eloquent;

/**
 * SystemDocument Model
 */
class SystemDocument extends Eloquent   {
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	protected $table = 'system_documents';
	
	
	public function getImageAttribute($value = ""){
		if(!empty($value) && file_exists(SYSTEM_DOCUMENT_ROOT_PATH.$value)){
			return SYSTEM_DOCUMENT_URL.$value;
		}else{
			return '';
		}
	}
	
	public function get_image($slug){
		return SystemDocument::where('slug',$slug)->value('image');
	}
}
