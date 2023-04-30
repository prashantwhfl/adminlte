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
												<th class="{{(($sortBy == 'value' && $order == 'desc') ? 'sorting_desc' : (($sortBy == 'value' && $order == 'asc') ? 'sorting_asc' : 'sorting'))}}">
													{{
														link_to_route(
															"$sectionName.index",
															trans("color"),
															array(
															'sortBy' => 'value',
															'order' => ($sortBy == 'value' && $order == 'desc') ? 'asc' : 'desc',
															$query_string
															)
														)
													}}
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
															<div class="text-dark-75 mb-1 font-size-lg">
															   <input type="color" value="{{ $result->value }}" disabled>
															</div>
														</td>
														<td class="text-right pr-2">
														<a href='{{route("$sectionName.edit","$result->id")}}' class="btn btn-icon btn-light btn-hover-primary btn-sm" data-toggle="tooltip" data-placement="top" data-container="body" data-boundary="window" title="" data-original-title="{{  trans('Edit') }}">
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