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
			{{ Form::hidden('display') }}
			<div class="row">
				<div class="col-12">
					<div class="card card-custom card-stretch card-shadowless">
						<div class="card-header">
							<div class="card-title">

							</div>
							<div class="card-toolbar">
								<a href="javascript:void(0);" class="btn btn-primary dropdown-toggle mr-2"
									data-toggle="collapse" data-target="#collapseOne6">
									Search
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
													<label>Section Name</label>
													{{ Form::text('name',((isset($searchVariable['name'])) ? $searchVariable['name'] : ''), ['class' => ' form-control','placeholder'=>'Name']) }}
												</div>
											</div>

											<div class="row mt-8">
												<div class="col-lg-12">
													<button class="btn btn-primary btn-primary--icon"
														id="kt_search">
														<span>
															<i class="la la-search"></i>
															<span>Search</span>
														</span>
													</button>
													&nbsp;&nbsp;
													
													<a href='{{ route("$modelName.index")}}'  class="btn btn-secondary btn-secondary--icon">
														<span>
															<i class="la la-close"></i>
															<span>Clear Search</span>
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
												<!-- <th class="{{(($sortBy == 'page_name' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'page_name' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$modelName.index",
															trans("Page Name"),
															array(
															'sortBy' => 'page_name',
															'order' => ($sortBy == 'page_name' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
												</th> -->
												<!-- <th class="{{(($sortBy == 'section_name' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'section_name' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$modelName.index",
															trans("Section Name"),
															array(
															'sortBy' => 'section_name',
															'order' => ($sortBy == 'section_name' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
												</th> -->
												<th class="{{(($sortBy == 'title' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'title' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$modelName.index",
															trans("Title"),
															array(
															'sortBy' => 'title',
															'order' => ($sortBy == 'title' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
												</th>
												<!-- <th class="{{(($sortBy == 'subtitle' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'subtitle' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$modelName.index",
															trans("Subtitle"),
															array(
															'sortBy' => 'subtitle',
															'order' => ($sortBy == 'subtitle' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
												</th> -->
												<th>
													Description
												</th>
												<!-- <th>
													Video URL
												</th>
												<th>
													Link
												</th> -->
												<th>
													Image
												</th>
												
												<th class="text-right">Action</th>
											</tr>
										</thead>
										<tbody>
											@if(!$results->isEmpty())
												@foreach($results as $result)
													<tr>
														<!-- <td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->page_name }}
															</div>
														</td> -->
														<!-- <td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->section_name }}
															</div>
														</td> -->
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->title }}
															</div>
														</td>
														<!-- <td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->subtitle }}
															</div>
														</td> -->

														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ strip_tags(Str::limit($result->description, 25)) }}
															</div>
														</td>
														<!-- <td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->video_url }}
															</div>
														</td>
														
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->link }}
															</div>
														</td> -->
														<td>
															<div class="text-dark-75 mb-1 font-size-lg">
																@if($result->image != "")
																	<br />
																	<a class="fancybox-buttons" data-fancybox-group="button" href="<?php echo SECTION_IMAGE_URL.$result->image; ?>">
																		<img class="" src="<?php echo SECTION_IMAGE_URL.$result->image; ?>" width="50px" height="50px">
																	</a>
																@endif
															</div>
														</td>
														<!-- <td>
															<div class="text-dark-75 mb-1 font-size-lg">
																{{ $result->block_order }}
															</div>
														</td> -->
														
														<td class="text-right pr-2">
															<a href='{{route("$modelName.edit","$result->id")}}'
																class="btn btn-icon btn-light btn-hover-primary btn-sm"
																data-toggle="tooltip" data-placement="top"
																data-container="body" data-boundary="window" title=""
																data-original-title="Edit">
																<span class="svg-icon svg-icon-md svg-icon-primary">
																	<svg xmlns="http://www.w3.org/2000/svg"
																		xmlns:xlink="http://www.w3.org/1999/xlink"
																		width="24px" height="24px" viewBox="0 0 24 24"
																		version="1.1">
																		<g stroke="none" stroke-width="1" fill="none"
																			fill-rule="evenodd">
																			<rect x="0" y="0" width="24" height="24" />
																			<path
																				d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
																				fill="#000000" opacity="0.3" />
																			<path
																				d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
																				fill="#000000" />
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