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
						 Add {{ $sectionNameSingular }} </h5>
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
			{{ Form::open(['role' => 'form','url' =>  route("$modelName.add"),'class' => 'mws-form', 'files' => true,"autocomplete"=>"off"]) }}
			
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
										    {!! HTML::decode( Form::label('name',trans("Name").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text("name",'', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('name') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('name') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('name',trans("Location").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text("location",'', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('location') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('location') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										    {!! HTML::decode( Form::label('rating',trans("Rating").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text("rating",'', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('rating') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('rating') ; ?></div>		
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-4">
										<!--begin::Input-->
										<div class="form-group">
										   {!! HTML::decode( Form::label('image', trans("Image").'<span class="text-danger"></span>')) !!}
											{{ Form::file('image', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('image') ? 'is-invalid':'')]) }}
											<div class="invalid-feedback"><?php echo $errors->first('image'); ?></div>
										</div>
										<!--end::Input-->
									</div>
									<div class="col-xl-8">
										<!--begin::Input-->
										<div class="form-group">
										     	{!! HTML::decode( Form::label('comment',trans("Comment").'<span class="text-danger">*</span>')) !!}
												{{ Form::textarea("comment",'', ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('comment') ? 'is-invalid':''),'id' => '']) }}
											<div class="invalid-feedback"><?php echo $errors->first('comment'); ?></div>
													
										</div>
										<!--end::Input-->
									</div>
								</div>
							</div>
						</div>
					<div class="d-flex justify-content-between border-top mt-5 pt-10">
						<div>
                            <a href='{{ route("$modelName.add")}}' class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>
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