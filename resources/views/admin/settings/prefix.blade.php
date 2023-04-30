@extends('admin.layouts.default')
@section('content') 
<!--begin::Content-->
	<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Subheader-->
		<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
			<div
				class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
				<!--begin::Info-->
				<div class="d-flex align-items-center flex-wrap mr-1">
					<!--begin::Page Heading-->
					<div class="d-flex align-items-baseline flex-wrap mr-5">
						<!--begin::Page Title-->
						<h5 class="text-dark font-weight-bold my-1 mr-5">
							{{ $prefix }} {{ trans("Setting") }} </h5>
						<!--end::Page Title-->

						<!--begin::Breadcrumb-->
						<ul
							class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
							<li class="breadcrumb-item">
								<a href="{{ route('dashboard')}}" class="text-muted">Dashboard</a>
							</li>
						</ul>
						<!--end::Breadcrumb-->
					</div>
					<!--end::Page Heading-->
				</div>
				<!--end::Info-->
		 	</div>
		</div>
		<!--end::Subheader-->

		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class=" container ">
				{{ Form::open(['role' => 'form','url' => 'adminpnlx/settings/prefix/'.$prefix,'class' => 'mws-form','id'=>'settingsForm']) }}
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-xl-1"></div>
							<div class="col-xl-10">
								<h3 class="mb-10 font-weight-bold text-dark">
									</h3>

								<div class="row">

								<?php 
								if(!empty($result)){
									$i = 0;
										$half = floor(count($result)/2);
									foreach ($result AS $setting) {
										$text_extention 	= 	'';
										$key				= 	$setting['key'];
										$keyE 				= 	explode('.', $key);
										$keyTitle 			= 	$keyE['1'];
									
										$label = $keyTitle;
										if ($setting['title'] != null) {
											$label = $setting['title'];
										}
									
										$inputType = 'text';
										if ($setting['input_type'] != null) {
											$inputType = $setting['input_type'];
										} ?>
										{{ Form::hidden("Setting[$i]['type']",$inputType) }}
										{{ Form::hidden("Setting[$i]['id']",$setting['id']) }}
										{{ Form::hidden("Setting[$i]['key']",$setting['key']) }}
										<?php 
										    switch($inputType){
											case 'checkbox': ?>
									<div class="col-xl-6">
										<div class="form-group">
											<label style="width:300px;"><?php echo $label; ?></label>
											<div class="mws-form-item clearfix">
												<ul class="mws-form-list inline">
													<?php 	
														$checked = ($setting['value'] == 1 )? true: false;
														$val	 = (!empty($setting['value'])) ? $setting['value'] : 0;
													?>
													{{ Form::checkbox("Setting[$i]['value']",$val,$checked) }} 
												</ul>
											</div>
										</div>
									</div>
									<?php
										break;	
										case 'text': ?>
										<div class="col-xl-6">
											<div class="form-group">
												<label  ><?php echo $label; ?></label>
												
												@if($key == "Social.facebook_link" || $key == "Site.android_link" || $key == "Site.iphone_link" || $key == "Social.instagram_link" || $key == "Social.twitter_link")
													{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'form-control form-control-solid form-control-lg ','id'=>$key]) }} 
												@else
													{{ Form::{$inputType}("Setting[$i]['value']",$setting['value'], ['class' => 'form-control form-control-solid form-control-lg  valid','id'=>$key]) }} 
												@endif
												<div class="invalid-feedback"></div>
											</div>
										</div>
									
									<?php
										break;
										case 'textarea': ?>
										<div class="col-xl-6">
											<div class="form-group">
												<label ><?php echo $label; ?></label>
												{{ Form::textarea("Setting[$i]['value']",$setting['value'], ['class' => 'form-control form-control-solid form-control-lg  textarea_resize',"rows"=>3,"cols"=>3]) }} 
											</div>
										</div>
									<?php	
										break;		
									}
									if($i == $half)
									   echo '</div><div class="row">';
									$i++;	
								}
							}
						?>
						</div>
								
								
								
								

								<div class="d-flex justify-content-between border-top mt-5 pt-10">
									<div>
										<a href="{{URL::to('adminpnlx/settings/prefix/'.$prefix)}}" class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>

										<a href="{{URL::to('adminpnlx/settings/prefix/'.$prefix)}}" class="btn btn-info font-weight-bold text-uppercase px-9 py-4">{{ trans('Cancel') }}</a>
									</div> 
									<div>
										<input type="button" onclick="submit_form();" value="{{ trans('Submit') }}" class="btn btn-success font-weight-bold text-uppercase px-9 py-4">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }} 
			</div>
		</div>
	</div>


	<script type="text/javascript">
		function isEmail(email) {
		  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		  return regex.test(email);
		}
	
		var empty_msg				=	'This field is required';
		var numuric_empty_msg		=	'This field is allow only numuric value';
		var image_validation		=	'Please upload a valid image. Valid extensions are jpg, jpeg, png, jpeg';
		var allowedExtensions		=	['gif','GIF','jpeg','JPEG','PNG','png','jpg','JPG'];
		function submit_form() {
			var $inputs = $('.mws-form :input.valid');
			var error  =	0;
			$inputs.each(function() { 
				if($(this).val().trim() == '' ){
					$(this).next().html(empty_msg);
					error	=	1;
				}else {
					if($(this).attr('id') == 'Site.email' ){
						if(!isEmail($(this).val().trim())) { 
							$(this).next().html("Please enter a valid email");
							error	=	1;
						}else {
							$(this).next().html("");
						}
					}else if($(this).attr('id') == 'Reading.records_per_page' ){
						if(!$.isNumeric($(this).val().trim())){
							$(this).next().html(numuric_empty_msg);
							error	=	1;
						}else {
							$(this).next().html("");
						}
					}else {
						$(this).next().html("");
					}
				}
			});
			if(error == 0){
				$('.mws-form').submit();
			}
		}
		$('#settingsForm').each(function() {
			$(this).find('input').keypress(function(e) {
			   if(e.which == 10 || e.which == 13) {
					submit_form();
					return false;
				}
			});
		});
	</script>
@stop
