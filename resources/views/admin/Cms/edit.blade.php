@extends('admin.layouts.default')
@section('content')
<!--begin::Content-->
<script src="{{ WEBSITE_JS_URL }}ckeditor/ckeditor.js"></script>
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
			{{ Form::open(['role' => 'form','url' =>  route("$modelName.edit",$model->id),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}
			<div class="card card-custom gutter-b">
				<!--<div class="card-header">
					<h3 class="card-title font-weight-bolder text-dark"> {{ $sectionNameSingular }} Information</h3>
				</div>-->
				<div class="card-body">
					<div class="row">
						<div class="col-xl-6">
							<!--begin::Input-->
							<div class="form-group">
								{!! HTML::decode( Form::label('page_name', trans("Page Name").'<span class="text-danger"> * </span>')) !!}
								{{ Form::text('page_name',$model->page_name, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('page_name') ? 'is-invalid':'')]) }}
								<div class="invalid-feedback"><?php echo $errors->first('page_name'); ?></div>
							</div>
							<!--end::Input-->
						</div>
						<div class="col-xl-6">
							<!--begin::Input-->
							<div class="form-group">
								{!! HTML::decode( Form::label('page_title', trans("Page Title").'<span class="text-danger"> * </span>')) !!}
								{{ Form::text('page_title',$model->title, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('title') ? 'is-invalid':'')]) }}
								<div class="invalid-feedback"><?php echo $errors->first('title'); ?></div>
							</div>
							<!--end::Input-->
						</div>
					</div>
				</div>
			</div>
			<div class="card card-custom gutter-b">
				<div class="card-body">
					<div class="tab-content">
						<div class="tab-pane fade show active" id="{{$model->title}}" role="tabpanel" aria-labelledby="{{$model->title}}">
							<div class="row">
								<div class="col-xl-12">	
									<div class="row">
										<div class="col-xl-12">
											<!--begin::Input-->
											<div class="form-group">
												<div id="kt-ckeditor-1-toolbar{{$model->id}}"></div>
													{!! HTML::decode( Form::label($model->id.'.body',trans("Description").'<span class="text-danger"> * </span>')) !!}
													
													{{ Form::textarea("body",$model->body, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('body') ? 'is-invalid':''),'id' => 'body_'.$model->id]) }}
													<div class="invalid-feedback"><?php echo $errors->first('body'); ?></div>
											</div>
											<script>
												/* CKEDITOR for description */
												CKEDITOR.replace( <?php echo 'body_'.$model->id; ?>,
												{
													filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
													enterMode : CKEDITOR.ENTER_BR
												});
												CKEDITOR.config.allowedContent = true;	
												
											</script>
											<!--end::Input-->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="d-flex justify-content-between border-top mt-5 pt-10">
						<!-- <div>
							<a href='{{ route("$modelName.edit",$model->id)}}' class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>

							<a href="{{ route($modelName.'.index')}}" class="btn btn-info font-weight-bold text-uppercase px-9 py-4">{{ trans('Cancel') }}</a>
						</div> -->
						<div>
							<button	button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4">
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