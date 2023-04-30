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
jQuery('.housing').on('click',function(){
	if(showHS == true){
		
		showHS = false;					
		var id = jQuery(this).attr('id').replace('housing_','');
		var campID = jQuery('#selectedCampProof').val();
		var ajax_url = jQuery('#ajaxAdminUrl').html();
		//var campID = '<?php echo $campID; ?>';
		jQuery("div#ajax_loading_div").addClass('show');
		if(id!=''){
			jQuery.ajax({	
				url : ajax_url,
				type: 'POST',
				data: {action:'show_housing',id:id,campID:campID},
				success: function(r){
					var res = JSON.parse(r)
					if(res.succes == true){
						contHTML = res.options;
						//console.log(contHTML);
						jQuery('#housingDetail').html('');
						jQuery('#housingDetail').append(contHTML);
						var maxAvailable 	= parseInt(jQuery('#housingMaximum_'+id).html());
						var used 			= parseInt(res.total);
						var available 		= maxAvailable-used;
						jQuery('#housingAvailable_'+id).html(available)
						jQuery('#housingUsed_'+id).html(used)
						jQuery('#selectHousingProof').val(1);
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



function add_jsHousingFunc(itemEl){
	var housingID = jQuery('div#droppable').data('housingid');
	var gender = jQuery('div#droppable').data('gender'); 
	if(gender == ''){
		gender = jQuery(itemEl).data('gender');
	}
	var entries_id = jQuery(itemEl).data('entries_id');
	var campID = jQuery(itemEl).data('cid');
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	
	jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
				 type : "post",
				 url : ajax_url,
				 data : {action: "addHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID,gender:gender},
				 success: function(r) {
					 if(r == 1){
						/* alert("action performed successfully");  */
						jQuery('#housing_'+housingID).trigger('click');
						var oldUsed 		= jQuery('#housingUsed_'+housingID).html();
						var oldAvailable 	= jQuery('#housingAvailable_'+housingID).html();
						var oldGender 		= jQuery('#housingGender_'+housingID).html();
						jQuery('#housingUsed_'+housingID).html(parseInt(oldUsed) + 1);
						jQuery('#housingAvailable_'+housingID).html(parseInt(oldAvailable) - 1);
						jQuery('#housingGender_'+housingID).html(gender);
						jQuery('div#droppable').data('gender',gender); 
						jQuery('div#droppable div.dragdrop').addClass('hasAttendee'); 
						jQuery("div#ajax_loading_div").removeClass('show'); 
					 } else {
						 if(r == 2){
							alert("Sorry no space in this housing. Please try again later.");
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
}


function remove_jsHousingFunc(item){ 
	var housingID = jQuery('div#droppable').data('housingid');
	var entries_id = jQuery(item).data('entries_id');
	var campID = jQuery(item).data('cid');
	var ajax_url = jQuery('#ajaxAdminUrl').html();
	setTimeout(function(){		
		jQuery("div#ajax_loading_div").addClass('show');
		jQuery.ajax({
					 type : "post",
					 url : ajax_url,
					 data : {action: "removeHousingAjax", housingID:housingID,entries_id:entries_id,campID:campID},
					 success: function(r) {
						 if(r == 1){
							/* alert("action performed successfully");  */
							jQuery("div#ajax_loading_div").removeClass('show'); 
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
						 } else {
							jQuery("div#droppable div.dragdrop").append(item);
							jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
							alert("Sorry there was an error. Please try again later.");  
							jQuery("div#ajax_loading_div").removeClass('show');
						 }						
					 },error: function(r) {
						jQuery("div#droppable div.dragdrop").append(item);
						jQuery("div.left_section div.dragdrop div#draggable_"+entries_id).remove();
						alert("Sorry there was an error. Please try again later."); 
						jQuery("div#ajax_loading_div").removeClass('show'); 
					 }
		}); 
	},2000); 	
}