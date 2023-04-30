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
							{{ 'Edit Setting' }} </h5>
						<!--end::Page Title-->

						<!--begin::Breadcrumb-->
						<ul
							class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
							<li class="breadcrumb-item">
								<a href="{{ route('dashboard')}}" class="text-muted">Dashboard</a>
							</li>
							<li class="breadcrumb-item">
								<a href="{{URL::to('adminpnlx/settings')}}" class="text-muted">{{ 'Back To Setting' }} </a>
							</li>
						</ul>
						<!--end::Breadcrumb-->
					</div>
					<!--end::Page Heading-->
				</div>
				<!--end::Info-->

				@include("admin.elements.quick_links")
			</div>
		</div>
		<!--end::Subheader-->

		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class=" container ">
				{{ Form::open(['role' => 'form','url' => 'adminpnlx/settings/edit-setting/'.$result->id,'class' => 'mws-form']) }}
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-xl-1"></div>
							<div class="col-xl-10">
								<h3 class="mb-10 font-weight-bold text-dark">
									</h3>

									<div class="row">
										<div class="col-xl-6">
											<!--begin::Input-->
											<div class="form-group">
												{!! HTML::decode( Form::label('title', trans("Title").'<span class="text-danger"> * </span>')) !!}
												{{ Form::text('title',$result->title, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('title') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('title'); ?></div>
											</div>
											<!--end::Input-->
										</div>
										<div class="col-xl-6">
											<!--begin::Input-->
											<div class="form-group">
												{!! HTML::decode( Form::label('key', trans("Key").'<span class="text-danger"> * </span>')) !!}
												{{ Form::text('key',$result->key, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('key') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('key'); ?></div>
												<small>e.g., 'Site.title'</small>
											</div>
											<!--end::Input-->
										</div>
										
										<div class="col-xl-6">
											<!--begin::Input-->
											<div class="form-group">
												{!! HTML::decode( Form::label('value', trans("Value").'<span class="text-danger"> * </span>')) !!}
												{{ Form::textarea('value',$result->value, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('value') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('value'); ?></div>
											</div>
											<!--end::Input-->
										</div>
										<div class="col-xl-6">
											<!--begin::Input-->
											<div class="form-group">
												{!! HTML::decode( Form::label('input_type', trans("Input Type").'<span class="text-danger"> * </span>')) !!}
												{{ Form::text('input_type',$result->input_type, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('input_type') ? 'is-invalid':'')]) }}
												<div class="invalid-feedback"><?php echo $errors->first('input_type'); ?></div>
												<small><em><?php echo "e.g., 'text' or 'textarea'";?></em></small>
											</div>
											<!--end::Input-->
										</div>
										<div class="col-xl-6">
											<!--begin::Input-->
											<div class="form-group">
												{!! HTML::decode( Form::label('editable', trans("Editable").'<span class="text-danger"> * </span>')) !!}
												{{ Form::checkbox('editable',null, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('editable') ? 'is-invalid':'')]) }}
												<input type="text" size="16" name="prependedInput2" id="prependedInput2" value="<?php echo "Editable"; ?>" disabled="disabled" style="width:415px;" class="small">
											</div>
											<!--end::Input-->
										</div>
	
									</div>
									
									
									
									
									
								
								<div class="d-flex justify-content-between border-top mt-5 pt-10">
									<div>
										<a href='{{URL::to('adminpnlx/settings/edit-setting/'.$result->id)}}' class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>
										
										<a href="{{URL::to('adminpnlx/settings')}}" class="btn btn-info font-weight-bold text-uppercase px-9 py-4">{{ trans('Cancel') }}</a>
									</div>
									<div>
										<button	button type="submit" class="btn btn-success font-weight-bold text-uppercase px-9 py-4">
											Submit
										</button>
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
@stop
