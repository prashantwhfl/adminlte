(function () {
	'use strict';

	var byId = function (id) { return document.getElementById(id); },

		loadScripts = function (desc, callback) {
			var deps = [], key, idx = 0;

			for (key in desc) {
				deps.push(key);
			}

			(function _next() {
				var pid,
					name = deps[idx],
					script = document.createElement('script');

				script.type = 'text/javascript';
				script.src = desc[deps[idx]];

				pid = setInterval(function () {
					if (window[name]) {
						clearTimeout(pid);

						deps[idx++] = window[name];

						if (deps[idx]) {
							_next();
						} else {
							callback.apply(null, deps);
						}
					}
				}, 30);

				document.getElementsByTagName('head')[0].appendChild(script);
			})()
		},

		console = window.console;


	if (!console.log) {
		console.log = function () {
			alert([].join.apply(arguments, ' '));
		};
	}


/* 	// Multi groups
	Sortable.create(byId('main'), {
		animation: 150,
		draggable: '.dragdrop',
		handle: '.dragableDiv'
	});

	[].forEach.call(byId('multi').getElementsByClassName('dragdrop'), function (el){
		Sortable.create(el, {
			group: 'photo',
			animation: 150
		});
	});
 */
 
var container = document.getElementById("left_id");
var sort = Sortable.create(container, {
  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
  handle: ".dragableDiv", // Restricts sort start click/touch to the specified element
  draggable: ".dragableDiv", // Specifies which items inside the element should be sortable
  onUpdate: function (evt/**Event*/){
     var item = evt.item; // the current dragged HTMLElement
	 /* console.log(evt); */
  }
});
 
 
 
 
/* var container = document.getElementById("main");
var sort = Sortable.create(container, {
  animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
  handle: ".dragableDiv", // Restricts sort start click/touch to the specified element
  draggable: ".dragableDiv" , // Specifies which items inside the element should be sortable
  onUpdate: function (evt){
     var item = evt.item; // the current dragged HTMLElement
	console.log(item);
  },
  onAdd: function (evt) {
        var itemEl = evt.item;  // dragged HTMLElement
        //evt.from;  // previous list
        // + indexes from onEnd
		console.log(evt);
    }

}); */
[].forEach.call(container.getElementsByClassName('dragdrop'), function (el){
		Sortable.create(el, {
			group: 'photo',
			animation: 150,
			onAdd: function (evt){
				var item = evt.item; // the current dragged HTMLElement
				//console.log('app add');
				//console.log(item);  
			},
			onRemove: function (evt){
				var item = evt.item; // the current dragged HTMLElement
				//console.log('app Remove');
				jQuery('.dragdrop').remove('#'+ evt.item.id);
				//console.log(item);  
			},
		});
	});
/* console.log(sort); */
// ..
sort.destroy();

	
})(); 


var showHS = true;
jQuery('.team').on('click',function(){
	if(showHS == true){
		showHS = false;					
		var id = jQuery(this).attr('id').replace('team_','');
		var campID = jQuery('#selectedCampProof').val();
		var selectAssingType = jQuery('#selectAssingType').val();
		var ajax_url = jQuery('#ajaxAdminUrl').html();
		//var campID = '<?php echo $campID; ?>';
		jQuery("div#ajax_loading_div").addClass('show');
		if(id!=''){
			jQuery.ajax({	
				url : ajax_url,
				type: 'POST',
				data: {action:'show_team',id:id,campID:campID,selectAssingType:selectAssingType},
				success: function(r){
					var res = JSON.parse(r)
					if(res.succes == true){
						contHTML = res.options;
						//console.log(contHTML);
						jQuery('#teamDetail').html('');
						jQuery('#teamDetail').append(contHTML);
						
						var maxAvailable 	= parseInt(jQuery('#teamMaximum_'+id).html());
						var used 			= parseInt(res.total);
						var available 		= maxAvailable-used;
						jQuery('#teamAvailable_'+id).html(available)
						jQuery('#teamUsed_'+id).html(used)
						jQuery('#selectTeamProof').val(1);
						jQuery("div#ajax_loading_div").removeClass('show');
						showHS = true;
					}else{
						alert("Sorry there was an error. Please try again later."); 
						jQuery("div#ajax_loading_div").removeClass('show');
						showHS = true;
					}
				}, 
				error: function(r){
					alert("Sorry there was an error. Please try again later."); 
					jQuery("div#ajax_loading_div").removeClass('show');
					showHS = true;
				}
			});
		}
		setTimeout(function(){ 
			dragdrop_resize();
		}, 3000);
	}
});




/* function add_jsTeamFunc(itemEl){
	var teamID = jQuery('div#droppable').data('teamid');
	var gender = jQuery('div#droppable').data('gender'); 
	var entries_id = jQuery(itemEl).data('entries_id');
	var campID = jQuery(itemEl).data('cid');
	var groupID = jQuery(itemEl).data('gid');
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	var type_assign = jQuery('#selectAssingType').val();
	jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
				 type : "post",
				 url : ajax_url,
				 data : {action: "addTeamAjax", teamID:teamID,entries_id:entries_id,campID:campID,gender:gender,groupID:groupID},
				 success: function(r) {
					 if(r == 1){
						jQuery('#team_'+teamID).trigger('click');
						var oldUsed 		= jQuery('#teamUsed_'+teamID).html();
						var oldAvailable 	= jQuery('#teamAvailable_'+teamID).html();
						var oldGender 		= jQuery('#teamGender_'+teamID).html();
						jQuery('#teamUsed_'+teamID).html(parseInt(oldUsed) + 1);
						jQuery('#teamAvailable_'+teamID).html(parseInt(oldAvailable) - 1);
						jQuery('#teamGender_'+teamID).html(gender);
						jQuery('div#droppable').data('gender',gender); 
						jQuery('div#droppable div.dragdrop').addClass('hasAttendee'); 
						jQuery("div#ajax_loading_div").removeClass('show'); 
					 } else {
						 if(r == 2){
							alert("Sorry no space in this team. Please try again later.");
						 } else if(r == 3){
							alert("Please select "+gender+" only"); 
						 } else if(r == 4){
							//alert("Attendee already exists. Please refresh your browser."); 
						 }else {							 
							alert("Sorry there was an error. Please try again later."); 
						 }
						jQuery("div#ajax_loading_div").removeClass('show');
						jQuery("div#droppable div#draggable_"+entries_id).remove();
						jQuery("div.left_section div.dragdrop").append(itemEl); 
					 }
					
				 },error: function(r) {
					jQuery("div#droppable div#draggable_"+entries_id).remove();
					jQuery("div.left_section div.dragdrop").append(itemEl); 
					jQuery("div#ajax_loading_div").removeClass('show'); 
				 }
	});
} */

function add_jsTeamFuncByChurch(itemEl){
	var teamID 			= jQuery('div#droppable').data('teamid');
	var reminig 		= jQuery('#remaingSpace_'+teamID).val();
	var gid 			= jQuery(itemEl).data("group_id");
	var teamGender 			= jQuery('div#droppable').data('gender');
	var total_attendee  = jQuery(itemEl).data("total_attendee");
	var total_male 		= jQuery(itemEl).data("total_male");
	var total_female 	= jQuery(itemEl).data("total_female");
	var popupContent 	= '';
	if(teamGender == 'Male'){
		var popupContent = '<input type="checkbox" name="selectAllMale" value="allMale" id="selectAllMale" class="optionCheck">All Male<br>Or<br>Enter Quantity <input type="text" name="customQuantityMail" id="customMales" value="" class="optionEnter"/><input type="hidden" name="grpupID" value="'+gid+'" id="grpupID">';
	}else if(teamGender == 'Female'){
		var popupContent = '<input type="checkbox" name="selectAllFemale" value="allFemale" id="selectAllFemale" class="optionCheck">All Female<br>Or<br>Enter Quantity <input type="text" name="customQuantity" value="" id="customFemales" class="optionEnter"/><input type="hidden" name="grpupID" value="'+gid+'" id="grpupID">';
	}else{
		var popupContent = '<input type="checkbox" name="selectAllMale" value="allMale" id="selectAllMale" class="optionCheck">All Male<br/><input type="checkbox" name="selectAllFemale" value="allFemale" id="selectAllFemale" class="optionCheck">All Female<br/>Or <br>Enter Quantity <input type="text" name="customQuantity" value=""id="customBoth" class="optionEnter"/><input type="hidden" name="grpupID" value="'+gid+'" id="grpupID">';
	}
	jQuery('.daynamicContent').html(popupContent);

	jQuery("#myModalSelect").modal('show');
}

	 
	jQuery(document).on( 'change',".optionEnter",function(){	
		jQuery(".optionCheck").prop('checked',false);
	}); 


	jQuery(document).on( 'change',".optionCheck",function(){  
		jQuery(".optionEnter").val(""); 
	}); 


jQuery("#addGroup").on("click",function(){
	var val='';
	var gender 			= ''; 
	var gid 			= jQuery('#grpupID').val();
	var teamID 			= jQuery('div#droppable').data('teamid');
	var teamGender 		= jQuery('div#droppable').data('gender');
	var cid 			= jQuery('#draggable_'+gid).data('cid');
	var total_attendee 	= jQuery('#draggable_'+gid).data('total_attendee');
	var total_male 		= jQuery('#draggable_'+gid).data('total_male');
	var total_female 	= jQuery('#draggable_'+gid).data('total_female');
	var reminig 		= jQuery('#remaingSpace_'+teamID).val();
	var ajax_url 		= jQuery('#ajaxAdminUrl').html();
	
	jQuery("div#ajax_loading_div").addClass('show');
	
	if(teamGender == 'Male'){
		var customQuantity = jQuery('#customMales').val();
		if (jQuery('#selectAllMale').prop('checked') == true){
           if(total_male <= reminig){
				val = total_male;
				gender = 'Male';
			}else{
				alert('Remaining Space is less then attendess.');
				return false;
			}
        }else if(parseInt(customQuantity) !='') {
			if(parseInt(customQuantity) <= parseInt(reminig)){
				val = customQuantity;
				gender = 'Male';
			}else{
				alert('Remaining Space is less then attendess.');
				return false;
			}
		}else{
			alert('Please Select an option Or enter quantity.');
			return false;
		}
	}else if(teamGender == 'Female'){
		var customQuantity = jQuery('#customFemales').val();
		if (jQuery('#selectAllFemale').prop('checked') == true){
           if(total_female <= reminig){
				val = total_female;
				gender = 'Female';
			}else{
				alert('Remaining Space is less then attendess.');
				return false;
			}
        }else if(customQuantity !='') {
			if(parseInt(customQuantity) <= parseInt(total_female)){
			
				if(parseInt(customQuantity) <= parseInt(reminig)){
					val = customQuantity;
					gender = 'Female';
				}else{
					alert('Remaining Space is less then attendess.');
					return false;
				}
			}else{
				alert('Total unsigned females are less then quantity');
				return false;
			}
		}else{
			alert('Please Select an option Or enter quantity.');
			return false;
		}
	}else{
		var customQuantity  = jQuery('#customBoth').val();
		if (jQuery('#selectAllFemale').prop('checked') == true && jQuery('#selectAllMale').prop('checked') == true){
           if(total_female <= reminig && total_male <= reminig){
				var valMale = parseInt(total_female);
				var valFemale = parseInt(total_male);
				val = parseInt(valMale+valFemale);
				if(val > reminig){
					alert('Remaining Space is less then attendess.');
					return false;
				}
			}else{
				alert('Remaining Space is less then attendess.');
				return false;
			}
			gender = 'Both';
        }else if (jQuery('#selectAllFemale').prop('checked') == true){
           if(total_female <= reminig){
				val = total_female;
				gender = 'Female';
			}else{
				alert('Remaining Space is less then  attendess.');
				return false;
			}
        }else if (jQuery('#selectAllMale').prop('checked') == true){
           if(total_male <= reminig){
				val = total_male;
				gender = 'Male';
			}else{
				alert('Remaining Space is less then attendess.');
				return false;
			}
        }else if(parseInt(customQuantity) !=''){
			if(parseInt(customQuantity) <= parseInt(reminig)){
				val = customQuantity;
				gender = 'Both';
			}else{
				alert('Remaining Space is less then  attendess.');
				return false;
			}
		}else{
			alert('Please Select an option Or enter quantity.');
			return false;
		}
	
	
	}
	jQuery.ajax({
				type : "post",
				url : ajax_url,
				data : {action: "addTeamAjax", teamID:teamID,cid:cid,gender:gender,gid:gid,val:val},
				beforeSend: function(){
					/* jQuery(".modal-body").find("div#ajax_loading_div1").addClass('show'); */
					jQuery("div#ajax_loading_div1").addClass('show');
				},
				success: function(r) {
					 if(r == 1){
						jQuery("#myModalSelect").modal('hide');
						jQuery('#team_'+teamID).trigger('click');
						jQuery("div#ajax_loading_div").addClass('show');
						add_group_section(cid);
						var oldUsed 		= jQuery('#teamUsed_'+teamID).html();
						var oldAvailable 	= jQuery('#teamAvailable_'+teamID).html();
						jQuery('#teamUsed_'+teamID).html(parseInt(oldUsed) + parseInt(val));
						jQuery('#teamAvailable_'+teamID).html(parseInt(oldAvailable) - parseInt(val)); 
						jQuery('#remaingSpace_'+teamID).val(parseInt(oldAvailable) - parseInt(val)); 
						jQuery('div#droppable div.dragdrop').addClass('hasAttendee'); 
						/* jQuery("div#ajax_loading_div").removeClass('show');  */
					 } else {
						 if(r == 2){
							alert("Sorry no space in this team. Please try again later.");
						 } else if(r == 3){
							
							alert("Please select "+gender+" only"); 
						 } else if(r == 4){
							//alert("Attendee already exists. Please refresh your browser."); 
						 }else {							 
							alert("Sorry there was an error. Please try again later."); 
						 }
						jQuery("#myModalSelect").modal('hide');
						jQuery('#team_'+teamID).trigger('click');
						add_group_section(cid);
						jQuery("div#ajax_loading_div").removeClass('show');
						jQuery("div#droppable div#draggable_"+gid).remove();
						jQuery("div.left_section div.dragdrop").append(itemEl); 
					 }
					
				 },error: function(r) {
					jQuery("#myModalSelect").modal('hide');
					jQuery('#team_'+teamID).trigger('click');
					add_group_section(cid);
					jQuery("div#droppable div#draggable_"+gid).remove(); 
					jQuery("div#ajax_loading_div").removeClass('show'); 
				 }
	});
	 
});
jQuery(document).on('click','.close',function(){
	jQuery("div#ajax_loading_div").removeClass('show');
	var gid 			= jQuery('#grpupID').val();
	var cid 			= jQuery('#draggable_'+gid).data('cid');
	var teamID 			= jQuery('div#droppable').data('teamid');
	jQuery("div#droppable div#draggable_"+gid).remove();
	add_group_section(cid);
	jQuery('#team_'+teamID).trigger('click');
	//jQuery("div.left_section div.dragdrop").append(itemEl);
});

function add_group_section(cid){
	jQuery("div#ajax_loading_div").addClass('show');
	var ajax_url 	= jQuery('#ajaxAdminUrl').html();
	if(cid!=''){
		jQuery.ajax({	
			url : ajax_url,
			type: 'POST',
			data: {action:'show_groups',cid:cid},
			success: function(r){
				var res = JSON.parse(r)
				if(res.succes == true){
					contHTML = res.options;
					//console.log(contHTML);
					jQuery('.leftGroupSection').html('');
					jQuery('.leftGroupSection').append(contHTML);
					jQuery("div#ajax_loading_div").removeClass('show');
				}else{
					alert("Sorry there was an error. Please try again later."); 
					jQuery("div#ajax_loading_div").removeClass('show');
				}
			}, 
			error: function(r){
				alert("Sorry there was an error. Please try again later."); 
				jQuery("div#ajax_loading_div").removeClass('show');
			}
		});
	}
};

function remove_jsTeamFunc(item){ 
	var teamID 		= jQuery('div#droppable').data('teamid');
	var group_id 	= jQuery(item).data('group_id');
	var gender 	= jQuery(item).data('gender');
	var campID 		= jQuery(item).data('cid');
	var ajax_url 	= jQuery('#ajaxAdminUrl').html();
	setTimeout(function(){		
		jQuery("div#ajax_loading_div").addClass('show');
		jQuery.ajax({
					 type : "post",
					 url : ajax_url,
					 data : {action: "removeTeamAjax", teamID:teamID,group_id:group_id,campID:campID,gender:gender},
					 success: function(r) {
						if(r == 1){
							jQuery('#team_'+teamID).trigger('click');
							add_group_section(campID);
						
							/* alert("action performed successfully");  */
							/* jQuery("div#ajax_loading_div").removeClass('show'); 
							jQuery('#team_'+teamID).trigger('click');
							if(jQuery('div#droppable .main_team_drag_data .drag_box').length  == 0){ 
								 jQuery("div#droppable .main_team_drag_data").removeClass('hasAttendee');
							} 
							var oldUsed 		= jQuery('#teamUsed_'+teamID).html();
							var oldAvailable 	= jQuery('#teamAvailable_'+teamID).html();
							var oldGender 		= jQuery('#teamGender_'+teamID).html();
							jQuery('#teamUsed_'+teamID).html(parseInt(oldUsed) - 1);
							jQuery('#teamAvailable_'+teamID).html(parseInt(oldAvailable) + 1);
							if(parseInt(oldUsed) - 1 == 0){								
								jQuery('#teamGender_'+teamID).html('N/A'); 
							} */
						} else {
							jQuery('#team_'+teamID).trigger('click');
							add_group_section(campID);
							alert("Sorry there was an error. Please try again later.");  
							jQuery("div#ajax_loading_div").removeClass('show');
						 }						
					 },error: function(r) {
						jQuery('#team_'+teamID).trigger('click');
						add_group_section(campID);
						alert("Sorry there was an error. Please try again later."); 
						jQuery("div#ajax_loading_div").removeClass('show'); 
					 }
		}); 
	},1000); 	
}





function saveAllAttendeesHousing(type){
	var housingProof = jQuery('#selectHousingProof').val();
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	
	var breakOut = false;
	if(housingProof == 1){
		var ttime = 1;
		var housingID = jQuery('div#droppable').data('housingid');
		var gender = jQuery('div#droppable').data('gender'); 
		/* jQuery("div.left_section div.dragdrop div.camperListing").each(function(){
			var campID = jQuery(this).data('cid');
			var gender2 = jQuery(this).data('gender');
			var entries_id = jQuery(this).data('entries_id');
			var itemEl = this;
			if((gender == '' || gender2 == gender) && gender2 == type){	
				ttime = parseInt(ttime) + 1;
			}
		});
		 */
		var is_show_msg = 1;
		jQuery("div.left_section div.dragdrop div.camperListing").each(function(){
			var campID = jQuery(this).data('cid');
			var gender2 = jQuery(this).data('gender');
			var entries_id = jQuery(this).data('entries_id');
			var itemEl = this;
			if((gender == '' || gender2 == gender) && gender2 == type){
				ttime = parseInt(ttime) + 1; 
				jQuery("div#ajax_loading_div").addClass('show');
				jQuery.ajax({
							 type : "post",
							 url : ajax_url,
							 data : {action: "addHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID,gender:gender},
							 success: function(r) {
								if(r == 1){
									jQuery("div#droppable div.dragdrop").append(itemEl);
									jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
									var oldUsed 		= jQuery('#housingUsed_'+housingID).html();
									var oldAvailable 	= jQuery('#housingAvailable_'+housingID).html();
									var oldGender 		= jQuery('#housingGender_'+housingID).html();
									jQuery('#housingUsed_'+housingID).html(parseInt(oldUsed) + 1);
									jQuery('#housingAvailable_'+housingID).html(parseInt(oldAvailable) - 1);
									if(oldGender == '' || oldGender == 'N/A'){										
										jQuery('#housingGender_'+housingID).html(gender2);
									}
									//alert("action performed successfully"); 
									//jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
									//jQuery("div.left_section div.dragdrop").append(itemEl);  
									if(gender == ''){										
										jQuery('div#droppable').data('gender',gender); 
									}
									//jQuery("div#ajax_loading_div").removeClass('show'); 
									//jQuery("div#ajax_loading_div").removeClass('show'); 
								} else {
									 if(r == 2){
										is_show_msg = 2;
										return false;
									 } else if(r == 3){
										//alert("Please select "+gender+" only"); 
									 } else if(r == 4){
										//alert("Attendee already exists. Please refresh your browser."); 
									 }else {							 
										//alert("Sorry there was an error. Please try again later."); 
									 }
									//jQuery("div#ajax_loading_div").removeClass('show'); 
									//jQuery("div#ajax_loading_div").removeClass('show'); 
								}
								
							 },error: function(r) {								
								//jQuery("div#ajax_loading_div").removeClass('show'); 
							 }
				});
				/* console.log(this);
				alert(jQuery(this).data('gender')); */
			}
		});
		
		var totleTime = parseInt(ttime) * 1000;
		setTimeout(function(){			
			if(is_show_msg == 2){
				//jQuery("div#ajax_loading_div").removeClass('show');
				//return false;
				alert("Sorry no space in this housing. Please try again later.");
			}
			jQuery('#housing_'+housingID).trigger('click');
			jQuery("div#ajax_loading_div").removeClass('show'); 
		},totleTime);
		
	} else {
		alert('Please select housing first.');
	}
	/* var gender = jQuery("div.left_section div.dragdrop.camperListing").data('gender');
	alert(gender); */
}





function removeAllChurchHS(groupID,housingID,gender){	
	if(!confirm('Are you sure, you want to remove all campers from this housing?')){
		return false;
	}else {		
		var housingProof = jQuery('#selectHousingProof').val();
		var ajax_url = jQuery('#ajaxAdminUrl').html();
		if(housingProof == 1){
			var ttime = 1;
			jQuery("div.mid_section div.dragdrop div.camperListing").each(function(){
				var campID = jQuery(this).data('cid');
				var gid = jQuery(this).data('gid');
				var gender2 = jQuery(this).data('gender');
				var entries_id = jQuery(this).data('entries_id');
				var itemEl = this;
				if(gender2 == gender && gid == groupID){	
					ttime = parseInt(ttime) + 1;
				}
			});
			jQuery("div.mid_section div.dragdrop div.camperListing").each(function(){ 
				var campID = jQuery(this).data('cid');
				var itemID = jQuery(this).attr('id');
				var gender2 = jQuery(this).data('gender');
				var gid = jQuery(this).data('gid');
				var entries_id = jQuery(this).data('entries_id');
				var itemEl = this;
				if(gender2 == gender && gid == groupID){	
					jQuery("div#ajax_loading_div").addClass('show');
					jQuery.ajax({
								 type : "post",
								 url : ajax_url,
								 data : {action: "removeHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID},
								 success: function(r) {
									 if(r == 1){
										jQuery('#housing_'+housingID).trigger('click');
										if(jQuery('div#droppable .main_housing_drag_data .drag_box').length  == 0){ 
											 jQuery("div#droppable .main_housing_drag_data").removeClass('hasAttendee');
										} 
										var oldUsed 		= jQuery('#housingUsed_'+housingID).html();
										var oldAvailable 	= jQuery('#housingAvailable_'+housingID).html();
										var oldGender 		= jQuery('#housingGender_'+housingID).html();
										jQuery('#housingUsed_'+housingID).html(parseInt(oldUsed) - 1);
										jQuery('#housingAvailable_'+housingID).html(parseInt(oldAvailable) + 1);
										if(parseInt(oldUsed) - 1 == 0){								
											jQuery('#housingGender_'+housingID).html('N/A'); 
										}
										jQuery("div.left_section div.dragdrop").append(itemEl);
										jQuery("div#mid_section div#"+itemID).remove();
									 } else {									
										alert("Sorry there was an error. Please try again later.");  
										jQuery("div#ajax_loading_div").removeClass('show');
									 }
									
								 },error: function(r) {
									alert("Sorry there was an error. Please try again later."); 
									jQuery("div#ajax_loading_div").removeClass('show'); 
								 }
					}); 
				}
			});
			var totleTime = parseInt(ttime) * 1000;
			setTimeout(function(){			
				//jQuery('#housing_'+housingID).trigger('click');
				jQuery("div#ajax_loading_div").removeClass('show'); 
			},totleTime);
		} else {
			alert('Please select housing first.');
		}
	}
}





function saveAllAttendeesHousingMultiple(type){
	var housingProof = jQuery('#selectHousingProof').val();
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	if(housingProof == 1){
		//alert('aaja');
		var ttime = 1;
		var housingID = jQuery('div#droppable').data('housingid');
		var gender = jQuery('div#droppable').data('gender'); 
		var is_show_msg = 1;
		jQuery("div.left_section div.dragdrop div.camperListing input.selectMultipleCamper:checked").each(function(){
			var ID = jQuery(this).parent('div').parent('div').parent('div').parent('div').attr('id');
			var campID = jQuery('#'+ID).data('cid');
			var gender2 = jQuery('#'+ID).data('gender');
			var entries_id = jQuery('#'+ID).data('entries_id');
			if((gender == '' || gender2 == gender) && gender2 == type){
				var itemEl = jQuery('#'+ID);
				ttime = parseInt(ttime) + 1; 
				jQuery("div#ajax_loading_div").addClass('show');
				jQuery.ajax({
							 type : "post",
							 url : ajax_url,
							 data : {action: "addHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID,gender:gender},
							 success: function(r) {
								if(r == 1){
									jQuery("div#droppable div.dragdrop").append(itemEl);
									jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
									var oldUsed 		= jQuery('#housingUsed_'+housingID).html();
									var oldAvailable 	= jQuery('#housingAvailable_'+housingID).html();
									var oldGender 		= jQuery('#housingGender_'+housingID).html();
									jQuery('#housingUsed_'+housingID).html(parseInt(oldUsed) + 1);
									jQuery('#housingAvailable_'+housingID).html(parseInt(oldAvailable) - 1);
									if(oldGender == '' || oldGender == 'N/A'){										
										jQuery('#housingGender_'+housingID).html(gender2);
									}
									//alert("action performed successfully"); 
									//jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
									//jQuery("div.left_section div.dragdrop").append(itemEl);  
									if(gender == ''){										
										jQuery('div#droppable').data('gender',gender); 
									}
									//jQuery("div#ajax_loading_div").removeClass('show'); 
									//jQuery("div#ajax_loading_div").removeClass('show'); 
								} else {
									 if(r == 2){
										is_show_msg = 2;
										return false;
									 } else if(r == 3){
										//alert("Please select "+gender+" only"); 
									 } else if(r == 4){
										//alert("Attendee already exists. Please refresh your browser."); 
									 }else {							 
										//alert("Sorry there was an error. Please try again later."); 
									 }
								}
								
							 },error: function(r) {								
								//jQuery("div#ajax_loading_div").removeClass('show'); 
							 }
				});
			}
		});		
		
		var totleTime = parseInt(ttime) * 1000;
		setTimeout(function(){			
			if(is_show_msg == 2){
				alert("Sorry no space in this housing. Please try again later.");
			}
			jQuery('#housing_'+housingID).trigger('click');
			jQuery("div#ajax_loading_div").removeClass('show'); 
		},totleTime);
		
	} else {
		alert('Please select housing first.');
	}
}



function removeAllSelectedCampers(){
	if(!confirm('Are you sure, you want to remove all selected campers from this housing?')){
		return false;
	}else {		
		var housingProof = jQuery('#selectHousingProof').val();
		var ajax_url = jQuery('#ajaxAdminUrl').html();
		if(housingProof == 1){
			var ttime = 1;
			var housingID = jQuery('div#droppable').data('housingid');
			var gender = jQuery('div#droppable').data('gender'); 
			var is_show_msg = 1;
			jQuery("div.mid_section div.camperListing input.selectMultipleCamper:checked").each(function(){
				var ID = jQuery(this).parent('div').parent('div').parent('div').parent('div').attr('id');
				var campID = jQuery('#'+ID).data('cid');
				var gender = jQuery('#'+ID).data('gender');
				var entries_id = jQuery('#'+ID).data('entries_id');
				var itemEl = jQuery('#'+ID);
				ttime = parseInt(ttime) + 1; 
				jQuery("div#ajax_loading_div").addClass('show');
				jQuery.ajax({
							 type : "post",
							 url : ajax_url,
							 data : {action: "removeHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID},
							 success: function(r) {
								 if(r == 1){
									jQuery('#housing_'+housingID).trigger('click');
									if(jQuery('div#droppable .main_housing_drag_data .drag_box').length  == 0){ 
										 jQuery("div#droppable .main_housing_drag_data").removeClass('hasAttendee');
									} 
									var oldUsed 		= jQuery('#housingUsed_'+housingID).html();
									var oldAvailable 	= jQuery('#housingAvailable_'+housingID).html();
									var oldGender 		= jQuery('#housingGender_'+housingID).html();
									jQuery('#housingUsed_'+housingID).html(parseInt(oldUsed) - 1);
									jQuery('#housingAvailable_'+housingID).html(parseInt(oldAvailable) + 1);
									if(parseInt(oldUsed) - 1 == 0){								
										jQuery('#housingGender_'+housingID).html('N/A'); 
									}
									jQuery("div.left_section div.dragdrop").append(itemEl);
									jQuery("div#mid_section div#"+ID).remove();
									jQuery("#selectcampers_"+entries_id).prop('checked',false);
								 } else {									
									alert("Sorry there was an error. Please try again later.");  
									jQuery("div#ajax_loading_div").removeClass('show');
								 }
								
							 },error: function(r) {
								alert("Sorry there was an error. Please try again later."); 
								jQuery("div#ajax_loading_div").removeClass('show'); 
							 }
				}); 
			});
			var totleTime = parseInt(ttime) * 1000;
			setTimeout(function(){			
				//jQuery('#housing_'+housingID).trigger('click');
				jQuery("div#ajax_loading_div").removeClass('show'); 
			},totleTime);
		}
	}
}



function add_limboHousingFunc(itemEl){
	//alert('aaya');
	jQuery(itemEl).addClass("limbo");
	gender = jQuery(itemEl).data('gender');
	var entries_id = jQuery(itemEl).data('entries_id');
	var campID = jQuery(itemEl).data('cid');
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
		 type : "post",
		 url : ajax_url,
		 data : {action: "addHousingAjaxLimbo",entries_id:entries_id,campID:campID,gender:gender},
		 success: function(r) {
			if(r == 1){
				jQuery("div#ajax_loading_div").removeClass('show');  
			} else {
				jQuery("div#ajax_loading_div").removeClass('show'); 
			}
		 },error: function(r) {			
			jQuery("div#ajax_loading_div").removeClass('show'); 
		 }
	});
}



function remove_limboHousingFunc(itemEl){
	gender = jQuery(itemEl).data('gender');
	var entries_id = jQuery(itemEl).data('entries_id');
	var campID = jQuery(itemEl).data('cid');
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
		 type : "post",
		 url : ajax_url,
		 data : {action: "removeHousingAjaxLimbo",entries_id:entries_id,campID:campID,gender:gender},
		 success: function(r) {
			if(r == 1){
				jQuery("div#ajax_loading_div").removeClass('show');  
			} else {
				jQuery("div#ajax_loading_div").removeClass('show'); 
			}
		 },error: function(r) {			
			jQuery("div#ajax_loading_div").removeClass('show'); 
		 }
	});
}




function moveToHousingFunc(campID,entriesIdArr,tp){  
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	 jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
		 type : "post",
		 url : ajax_url,
		 data : {action: "move_to_housing",campID:campID,entries_id:entriesIdArr,tp:tp},
		 success: function(r) {
			var loc = window.location;
			if(r == 1){
				jQuery("div#ajax_loading_div").removeClass('show');
				window.location.replace(loc);
			} else if(r != 0) {
				alert(r);
				jQuery("div#ajax_loading_div").removeClass('show'); 
				window.location.replace(loc);
			} else {
				alert('Sorry there was an error. Please try again later.');
				jQuery("div#ajax_loading_div").removeClass('show');
				window.location.replace(loc);
			}
		 },error: function(r) {	
			alert('Sorry there was an error. Please try again later.');
			jQuery("div#ajax_loading_div").removeClass('show'); 
			window.location.replace(loc);
		 }
	}); 
}