@extends('admin.layouts.default')
@section('content')
<script src="{{ WEBSITE_JS_URL }}ckeditor/ckeditor.js"></script>
<!--begin::Content-->
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Subheader-->
	<div class="subheader py-2 py-lg-4  subheader-solid " id="kt_subheader">
		<div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
			<!--begin::Info-->
			<div class="d-flex align-items-center flex-wrap mr-1">
				<!--begin::Page Heading-->
				<div class="d-flex align-items-baseline flex-wrap mr-5">
					<!--begin::Page Title-->
					<h5 class="text-dark font-weight-bold my-1 mr-5">
						 Edit {{ $sectionNameSingular }} </h5>
					<!--end::Page Title-->

					<!--begin::Breadcrumb-->
					<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
						<li class="breadcrumb-item">
							<a href="{{ route('dashboard')}}" class="text-muted">Dashboard</a>
						</li>
						<li class="breadcrumb-item">
							<a href="{{ route($modelName.'.index')}}" class="text-muted">{{ $sectionName }}</a>
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
			{{ Form::open(['role' => 'form','url' =>  route("$modelName.edit",[$model->id]),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}
			
			<div class="card card-custom gutter-b">
				<div class="card-header card-header-tabs-line">
					<div class="card-toolbar border-top">
						
				</div>
				<div class="card-body">
						<div class="row">
							<div class="col-xl-12">	
								<div class="row">
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('section_name',trans("Section Name").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text("section_name",$model->section_name, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('section_name') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('section_name') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('title',trans("Title").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text("title",$model->title, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('title') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('title') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('subtitle',trans("Subtitle").'<span class="text-danger">  </span>')) !!}
											{{ Form::text("subtitle",$model->subtitle, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('subtitle') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('subtitle') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('video_url',trans("Video Url").'<span class="text-danger">  </span>')) !!}
											{{ Form::text("video_url",$model->video_url, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('video_url') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('video_url') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('link',trans("Link").'<span class="text-danger">  </span>')) !!}
											{{ Form::text("link",$model->link, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('link') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('link') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										
										<div class="form-group">
										   {!! HTML::decode( Form::label('image', trans("Image").'<span class="text-danger"></span>')) !!}
											{{ Form::file('image', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('image') ? 'is-invalid':'')]) }}
											<div class="invalid-feedback"><?php echo $errors->first('image'); ?></div>
										</div>
										
									</div>
									<div class="col-xl-8">
										<!--begin::Input-->
										<div class="form-group">
										     	{!! HTML::decode( Form::label('description',trans("Description").'<span class="text-danger"></span>')) !!}
												{{ Form::textarea("description",$model->description, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('description') ? 'is-invalid':''),'id' => 'description'.$model->id]) }}
											<div class="invalid-feedback"><?php echo $errors->first('description'); ?></div>
													
										</div>
										<!--end::Input-->
										<script>
											/* CKEDITOR for description */
											CKEDITOR.replace( <?php echo 'description'.$model->id; ?>,
											{
												filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
												enterMode : CKEDITOR.ENTER_BR
											});
											CKEDITOR.config.allowedContent = true;	
											
										</script>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										@if($model->image != "")	
											<br />
											<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo SECTION_IMAGE_URL.$model->image; ?>"><img height="200" width="200" src="<?php echo SECTION_IMAGE_URL.$model->image; ?>" /></a>
										@endif		
									</div>
										
								</div>
							</div>
						</div>
					<div class="d-flex justify-content-between border-top mt-5 pt-10">
						<div>
                            <a href='{{ route("$modelName.edit",$model->id)}}' class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>
                            <a href="{{ route($modelName.'.index')}}" class="btn btn-info font-weight-bold text-uppercase px-9 py-4">{{ trans('Cancel') }}</a>
                        </div>
                        <div>
                            <button	type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4">
                                Submit
                            </button>
                        </div>
					</div>
				</div>
			</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop