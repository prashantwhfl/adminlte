<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Spatie\Activitylog\Traits\LogsActivity;
// use Spatie\Activitylog\Traits\CausesActivity;

 
class Admin extends Authenticatable
{
 
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function restaurant() { 
        return $this->hasMany('App\Model\Admin', 'parent_id')->with('sub_admins');
    }

    public function sub_admins() { 
        return $this->hasMany('App\Model\Admin', 'parent_id');
    }
      
    public function user_purchased_plans() { 
        return $this->hasMany('App\Model\UserPlan', 'user_id');
    }

    public function current_active_plan() { 
        return $this->hasOne('App\Model\UserPlan', 'user_id')->where("is_cancelled",0)->where("end_date",'>=',date("Y-m-d"))->orderBy("id","DESC");
    }
      
	public function getRestaurantLogoAttribute($value = ""){
		if(!empty($value) && file_exists(RESTAURANT_LOGO_IMAGE_ROOT_PATH.$value)){
			return RESTAURANT_LOGO_IMAGE_URL.$value;
		}else{
            return WEBSITE_IMG_URL.'krunch-logo-blue.png';
        }
	}
	public function getRestaurantFavIconAttribute($value = ""){
		if(!empty($value) && file_exists(RESTAURANT_FEVICON_IMAGE_ROOT_PATH.$value)){
			return RESTAURANT_FEVICON_IMAGE_URL.$value;
		}else{
            return WEBSITE_IMG_URL.'fav.png';
        }
	}
}