
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

	
	
	function dragdrop_resize(){ 
		var midHeight = jQuery('#housingDetail').height() - 150;
		jQuery('.left_section div.dragdrop').css("height",midHeight); 
		jQuery('.left_section div.dragdrop').css("min-height","600px"); 
	}
	var showHS = true;
	jQuery('.team').on('click',function(){
		if(showHS == true){
			showHS = false;					
			var id = jQuery(this).attr('id').replace('team_','');
			var campID = jQuery('#selectedCampProof').val();
			var groupID = jQuery('#selectedgroup').val();
			var ajax_url = jQuery('#ajaxAdminUrl').html();
			if(id!=''){
				jQuery("div#ajax_loading_div").addClass('show');
				jQuery.ajax({	
					url : ajax_url,
					type: 'POST',
					data: {action:'vfb_show_team_for_church',id:id,campID:campID,groupID:groupID},   
					beforeSend: function(){
						jQuery("div#ajax_loading_div").addClass('show');
					   },
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
				
/*ADD TO RTEAM */

function add_jsTeamFunc(itemEl){
	var teamID = jQuery('div#droppable').data('teamid');
	var gender = jQuery('div#droppable').data('gender'); 
	if(gender == ''){
		gender = jQuery(itemEl).data('gender');
	}
	var entries_id = jQuery(itemEl).data('entries_id');
	var campID = jQuery(itemEl).data('cid');
	var group_id = jQuery(itemEl).data('group_id');
	var ajax_url = jQuery('#ajaxAdminUrl').html(); 
	jQuery("div#ajax_loading_div").addClass('show');
	jQuery.ajax({
		type : "post",
		url : ajax_url,
		data : {action: "addTeamAjaxForChurch", teamID:teamID,entries_id:entries_id,campID:campID,group_id:group_id,gender:gender},
		success: function(r) { 
			if(r == 1){ 
				jQuery('#team_'+teamID).trigger('click');
				jQuery("div#ajax_loading_div").addClass('show');
				var oldUsed 		= jQuery('#teamUsed_'+teamID).html();
				var oldAvailable 	= jQuery('#teamAvailable_'+teamID).html();
				var oldGender 		= jQuery('#teamGender_'+teamID).html();
				jQuery('#teamUsed_'+teamID).html(parseInt(oldUsed) + 1);
				jQuery('#teamAvailable_'+teamID).html(parseInt(oldAvailable) - 1);
				jQuery('#teamGender_'+teamID).html(gender);
				jQuery('div#droppable').data('gender',gender); 
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

