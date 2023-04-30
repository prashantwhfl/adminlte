<?php
//use App\Model\Language;
//use App\Model\Block;
use App\Models\SystemDocument;
use App\Models\Links;
class CustomHelper {
	
	public static function  addhttp($url = "") {
		if($url == ""){
			return "";
		}
		if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			$url = "http://" . $url;
		}
		return $url;
	}
	

    public static function getSystemDocumentBySlug($slug){
		$sysDocument		=	SystemDocument::query()
								->where("slug",$slug)
								->value("image");
		return $sysDocument;
	}




	public static function get_link_by_type($type){
		$link		=	Links::query()
								->where("type",$type)
								->value("value");
		return $link;
	}
/* 
	public static function sideBarNavigation($menus){
	
		$treeView	=	"";  
		$segment2	=	Request::segment(1); 
		$segment3	=	Request::segment(2); 
		$segment4	=	Request::segment(3);  
		$segment5	=	Request::segment(4);
		if(Session::has('default_restaurant')) {
			$restDetails        =       Session::get('default_restaurant'); 
			if(!empty($restDetails)){
				$restaurantDetails    =   CustomHelper::getRestaurentDetailById($restDetails->restaurant_unique_id);
			}
		}else{
			$restDetails          =       "";
			$restaurantDetails    =   CustomHelper::getRestaurentDetailById(Auth::user()->restaurant_unique_id);
		} 
 		if(!empty($menus)){
			$treeView	.=	"<ul class='menu-nav'>";
			foreach($menus as $record){
				$currentSection		=	"";
				$currentPlugin		=	"";
				$plugin				=	explode('/',$record->path); 
				$pluginSlug3		=	isset($plugin[3])?$plugin[3]:''; 
				$myArray			=	[];
				$myArray1			=	[];
				if(!empty($record->children)){
					$plugin_array	=	"";
					$plugin_array1	=	"";
					foreach($record->children as $li_record){
						$plugin				=	explode('/',$li_record->path); 
						$slug				=	isset($plugin[0])?$plugin[0]:''; 
						$slug1				=	isset($plugin[1])?$plugin[1]:'';
						$plugin_array 		.= 	"".$slug.",";
						$plugin_array1 		.= 	"".$slug1.",";
					}
					$myArray 		= 	explode(',', $plugin_array);
					$myArray1 		= 	explode(',', $plugin_array1);
				}
				$class 				= 	(in_array($segment2,$myArray1) && ($segment2 != '')) ? 'menu-item-open':'';
				$classActive 		= 	((in_array($segment2,$myArray1) && ($segment2 != ''))) ? 'menu-item-active':'';
				$style 				= 	(in_array($segment2,$myArray1) && ($segment2 != '')) ? 'display:block;':'display:none;';
				
				$classActive1 = "";
				$path	=	((!empty($record->path) && ($record->path != 'javascript::void()') && ($record->path != 'javascript::void(0)') && ($record->path != 'javascript:void()') && ($record->path != 'javascript::void();'))?URL($record->path):'javascript:void()');
				$second_icon	=	((!empty($record->path) && ($record->path == 'javascript::void()') || ($record->path == 'javascript::void(0)') || ($record->path == 'javascript:void()') || ($record->path == 'javascript::void();'))?'menu-arrow':'');
				
				
				if((!empty($record->path) && ($record->path != 'javascript::void()') && ($record->path != 'javascript::void(0)') && ($record->path != 'javascript:void()') && ($record->path != 'javascript::void();'))){
					$pluginData			=	explode('/',$record->path);  
					$plugin				=	isset($pluginData[0])?$pluginData[0]:''; 
					$plugin1			=	isset($pluginData[1])?$pluginData[1]:''; 
					$classActive1		=	((($plugin == $segment2 && ($plugin1 == "")) || ($plugin1 == $segment2))?'menu-item-active':'');	
				}  
				$treeView .= "<li class='menu-item menu-item-submenu ".(!empty($record->children)? $class:' ').'  '.$classActive1."' aria-haspopup='true' data-menu-toggle='hover'><a href='".$path."' class='menu-link menu-toggle'>".$record->icon."<span class='menu-text'>".$record->title."</span><i class='".$second_icon."'></i></a>";
			 
				
				if(!empty($record->children)){
					
					//$treeView	.=	"<ul class='treeview-menu '".$class."' style='".$style."'>";
					$treeView	.= "<div class='menu-submenu'><i class='menu-arrow'></i><ul class='menu-subnav'><li class='menu-item menu-item-parent' aria-haspopup='true'>
					<span class='menu-link'>
						<span class='menu-text'>".$record->title."</span>
					</span>
				</li>";
					foreach($record->children as $li_record){
						$path	=	((!empty($li_record->path) && ($li_record->path != 'javascript::void()') && ($li_record->path != 'javascript::void(0)') && ($li_record->path != 'javascript:void()'))?URL($li_record->path):'javascript:void()');
						
						$second_icon	=	((!empty($li_record->path) && ($li_record->path == 'javascript::void()') || ($li_record->path == 'javascript::void(0)') || ($li_record->path == 'javascript:void()') || ($li_record->path == 'javascript::void();'))?'fa fa-angle-left pull-right':'');
						
						$plugin			=	explode('/',$li_record->path); 
						$currentPlugin	=	isset($plugin[1])?$plugin[1]:'';
						$currentPlugin1	=	isset($plugin[2])?$plugin[2]:''; 
						
						$requestPlugin		=	explode('/',Request::path()); 
						$requestSegment		=	isset($requestPlugin[0])?$requestPlugin[0]:'';
						$requestSegment1	=	isset($requestPlugin[1])?$requestPlugin[1]:'';
						 
						$activeClass		=	($currentPlugin == $segment2)?"menu-item-active":'';
						//$treeView .= "<li class='".$activeClass.(!empty($li_record->children)?(' treeview '):'')."'><a href='".$path."'><i class='".$li_record->icon."'></i>"."<span>".$li_record->title."</span>"."<i class='".$second_icon."'></i></a>"; 
					 
								$treeView .= "<li class='menu-item ".$activeClass."'  aria-haspopup='true'>
								<a href='".$path."' class='menu-link'>
									<i class='menu-bullet menu-bullet-line'>
										<span></span>
									</i>
									<span class='menu-text'>".$li_record->title."</span>
								</a>"; 
						if(!empty($li_record->children)){ 
							$treeView  .= CustomHelper::sideBarNavigation($li_record->children);
						} 
						$treeView  .= "</li>"; 
					}
					$treeView  .= "</ul></div>";
				} 
				$treeView  .= "</li>"; 
			}
			$treeView  .= "</ul>";
		}  
		
		return $treeView;
	} */



}
