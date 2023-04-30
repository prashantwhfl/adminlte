@extends('admin.layouts.default')
@section('content') 

<?php
	$userInfo	=	Auth::guard("admin")->user();
	$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
	$name		=	(isset($userInfo->name)) ? $userInfo->name : '';
?>
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
							My Account </h5>
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

				@include("admin.elements.quick_links")
			</div>
		</div>
		<!--end::Subheader-->

		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class=" container ">
				{{ Form::open(['role' => 'form','url' => 'adminpnlx/myaccount','class' => 'mws-form','files'=>'true']) }}
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-xl-1"></div>
							<div class="col-xl-10">
								<h3 class="mb-10 font-weight-bold text-dark"></h3>
								<div class="row">
									<div class="col-xl-6">
										<!--begin::Input-->
										<div class="form-group">
											{!! HTML::decode( Form::label('name', trans("Name").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text('name',$name, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('name') ? 'is-invalid':'')]) }}
											<div class="invalid-feedback"><?php echo $errors->first('name'); ?></div>
										</div>
										<!--end::Input-->
									</div>
								</div>
								<div class="row">
									<div class="col-xl-6">
										<!--begin::Input-->
										<div class="form-group">
											{!! HTML::decode( Form::label('email', trans("Email").'<span class="text-danger"> * </span>')) !!}
											{{ Form::text('email',$email, ['class' => 'form-control form-control-solid form-control-lg '.($errors->has('email') ? 'is-invalid':'')]) }}
											<div class="invalid-feedback"><?php echo $errors->first('email'); ?></div>
										</div>
										<!--end::Input-->
									</div>
								</div>
								
								<div class="d-flex justify-content-between border-top mt-5 pt-10">
									<div>
										<a href="{{URL::to('adminpnlx/myaccount')}}" class="btn btn-danger font-weight-bold text-uppercase px-9 py-4">{{ trans('Clear') }}</a>

										<a href="{{URL('adminpnlx/dashboard')}}" class="btn btn-info font-weight-bold text-uppercase px-9 py-4">{{ trans('Cancel') }}</a>
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
