<?php 
namespace App\Models; 
use Eloquent,Session;
use App;
use DB;
/**
 * Category Model
 */
 
class Service extends Eloquent {
/**
 * The database table used by the model.
 */
	protected $table = 'services';

    public function getImageAttribute($value){
        //return WEBSITE_ADMIN_SERVICE_IMG_URL.$value;
        if(!empty($value)){
            return WEBSITE_ADMIN_SERVICE_IMG_URL.$value;
        }else{
            return WEBSITE_ADMIN_SERVICE_IMG_URL.'no-image.png';
        }
    }

    public function getImage_hoverAttribute($value){
        if(!empty($value) && file_exists(WEBSITE_ADMIN_SERVICE_IMG_ROOT_PATH.$value)){
            return WEBSITE_ADMIN_SERVICE_IMG_URL.$value;
        }else{
            return WEBSITE_ADMIN_SERVICE_IMG_URL.'no-image.png';
        }
    }
}
