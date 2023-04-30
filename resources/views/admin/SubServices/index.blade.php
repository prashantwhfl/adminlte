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
						{{ $sectionName }} </h5>
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
			{{ Form::open(['method' => 'get','role' => 'form','url' => route("$modelName.index"),'class' => 'kt-form kt-form--fit mb-0','autocomplete'=>"off"]) }}
			<div class="row">
				<div class="col-12">
					<div class="card card-custom card-stretch card-shadowless">
					<div class="card-header">
							<div class="card-title">

							</div>
							<div class="card-toolbar">
								<a href="javascript:void(0);" class="btn btn-primary dropdown-toggle mr-2"
									data-toggle="collapse" data-target="#collapseOne6">
									{{ trans("Search") }}
								</a>
								<a href= '{{ route("$sectionName.add")}}'   class="btn btn-primary"> 
									{{ trans("Add New Sub Services") }} 
								</a>
								
							</div>
						</div>
						<div class="card-body">
							<div class="accordion accordion-solid accordion-toggle-plus"
								id="accordionExample6">
								<div id="collapseOne6" class="collapse <?php echo !empty($searchVariable) ? 'show' : ''; ?>" data-parent="#accordionExample6">
									<div>
										<div class="row mb-6">
											<div class="col-lg-3 mb-lg-5 mb-6">
												<label>{{ trans("Name") }}</label>
												{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => ' form-control','placeholder'=>trans("Name")]) }}
											</div>
										 </div>
										<div class="row mt-8">
											<div class="col-lg-12">
												<button class="btn btn-primary btn-primary--icon"
													id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>{{ trans("Search") }}</span>
													</span>
												</button>
												&nbsp;&nbsp;
												
												<a href='{{ route("$sectionName.index")}}'  class="btn btn-secondary btn-secondary--icon">
													<span>
														<i class="la la-close"></i>
														<span>{{ trans("Clear Search") }}</span>
													</span>
												</a>
											</div>
										</div>
										
										<!--begin: Datatable-->
										<hr>
									</div>
								</div>
							</div>
							<div class="dataTables_wrapper ">
								<div class="table-responsive">
									<table
										class="table dataTable table-head-custom table-head-bg table-borderless table-vertical-center"
										id="taskTable">
										<thead>
											<tr class="text-uppercase">
												<th class="{{(($sortBy == 'name' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'name' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$sectionName.index",
															trans("Name"),
															array(
															'sortBy' => 'name',
															'order' => ($sortBy == 'name' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
												</th>
												
												<th class="">
													Service
												</th>
												<th class="">
													Description
												</th>
												
												<th class="">
													Image
												</th>
												
												<th class="">
													Status
												</th>
										
												<th class="text-right">{{ trans("action") }}</th>
											</tr>
										</thead>
										<tbody>
											@if(!$results->isEmpty())
												@foreach($results as $result)
													<tr>
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->name }}
															</div>
														</td>
														<td>
															{{ $result->sub_service_name }}
														</td>
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->description }}
															</div>
														</td>
														
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">   
																<br />
																@if(!empty($result->image))
																<a class="fancybox-buttons" data-fancybox-group="button" href="{{SUB_SERVICES_IMAGE_URL.$result->image}}">
																	<img class="" src="{{SUB_SERVICES_IMAGE_URL.$result->image}}" width="50px" height="50px">
																</a>
																@else
																<a class="fancybox-buttons" data-fancybox-group="button" href="{{WEBSITE_IMG_URL.'no-image.png'}}">
																	<img class="" src="{{WEBSITE_IMG_URL.'no-image.png'}}" width="50px" height="50px">
																</a>
																@endif
															</div>
														</td>
														
														<td>
															@if($result->status	== 1)
																<span class="label label-lg label-light-success label-inline">Activated</span>
															@else
																<span class="label label-lg label-light-danger label-inline">Deactivated</span>
															@endif
														
														</td>
														
														<td class="text-right pr-2">
														
																@if($result->status == 1)
																<a  title="Click To Deactivate" href='{{route("$modelName.status",array($result->id ,$result->status))}}' class="btn btn-icon btn-light btn-hover-danger btn-sm status_any_item" data-toggle="tooltip" data-placement="top"data-container="body" data-boundary="window"data-original-title="Deactivate">
																	<span class="svg-icon svg-icon-md svg-icon-danger">
																		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																				<g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)" fill="#000000">
																					<rect x="0" y="7" width="16" height="2" rx="1"/>
																					<rect opacity="0.3" transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000) " x="0" y="7" width="16" height="2" rx="1"/>
																				</g>
																			</g>
																		</svg>
																	</span>
																</a>
															@else
																<a title="Click To Activate" href='{{route("$modelName.status",array($result->id,$result->status))}}' class="btn btn-icon btn-light btn-hover-success btn-sm status_any_item" data-toggle="tooltip" data-placement="top"data-container="body" data-boundary="window"data-original-title="Activate">
																	<span class="svg-icon svg-icon-md svg-icon-success">
																		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																				<polygon points="0 0 24 0 24 24 0 24"/>
																				<path d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) "/>
																				<path d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) "/>
																			</g>
																		</svg>
																	</span>
																</a> 
															
															@endif 
														<a href='{{route("$sectionName.edit","$result->id")}}' class="btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-placement="top" data-container="body" data-boundary="window" title="" data-original-title="{{  trans('messages.restro.edit') }}">
																<span class="svg-icon svg-icon-md svg-icon-primary">
																<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																	<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																		<rect x="0" y="0" width="24" height="24"/>
																		<path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
																		<rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
																	</g>
																</svg>
															</span>
														</a>
														<a href='{{route("$sectionName.delete","$result->id")}}'
																class="btn btn-icon btn-light btn-hover-danger btn-sm confirmDelete"
																data-toggle="tooltip" data-placement="top"
																data-container="body" data-boundary="window" title=""
																data-original-title="{{  trans('Delete') }}">
																<span class="svg-icon svg-icon-md svg-icon-danger">
																	<svg xmlns="http://www.w3.org/2000/svg"
																		xmlns:xlink="http://www.w3.org/1999/xlink"
																		width="24px" height="24px" viewBox="0 0 24 24"
																		version="1.1">
																		<g stroke="none" stroke-width="1" fill="none"
																			fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24" />
																			<path
																				d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z"
																				fill="#000000" fill-rule="nonzero" />
																			<path
																				d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
																				fill="#000000" opacity="0.3" />
																		</g>
																	</svg>
																</span>
															</a>
													  </td>
													</tr>
												@endforeach  
											@else
												<tr>
													<td colspan="6" style="text-align:center;"> {{ trans("Record not found.") }}</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
								@include('pagination.default', ['results' => $results])
							</div>
						</div>
					</div>
				</div>
			</div>
			{{ Form::close() }} 
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->

<script>
	$(document).ready(function () {
		$('#datepickerfrom').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		$('#datepickerto').datetimepicker({
			format: 'YYYY-MM-DD'
		});
		
	});
	
	function page_limit() {
		$("form").submit();
	}
</script>

@stop