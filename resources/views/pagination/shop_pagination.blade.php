@if(!empty($suppProdDetails))
@if(!$suppProdDetails->isEmpty())
<div class="row align-items-center">
    <div class="col"> 
        <div class="pb-3">   {{ trans('messages.global.showing')}} {{ $results->firstItem() }} {{ trans('messages.global.to')}} {{ $results->lastItem() }} {{ trans('messages.global.of')}} {{$results->total()}} {{ trans('messages.global.entries')}}</div>
    </div>
</div>
<div class="row align-items-center pb-2">
    <div class="col">
        <div class="d-flex align-items-center">
            {{ trans('messages.global.show')}}  
            {{ Form::select('per_page',[config::get("Reading.record_per_page") => trans('messages.global.default')] + Config::get('per_page'),((isset($searchVariable['per_page'])) ? $searchVariable['per_page'] : ''), ['class' => 'mx-2 select-filter','onchange'=>'page_limit()','id'=>'per_page']) }}
        </div>
    </div>
    <?php
        $link_limit = 6; 
    ?>
    <div class="col-auto">
        @if ($results->lastPage() > 1)
            <nav aria-label="Page navigation example " class="shop-pagination d-none d-sm-block">
                <ul class="pagination">
                    @if ($results->onFirstPage())
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" onclick="paginate('prev')" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li> 
                    @endif
                    @for ($i = 1; $i <= $results->lastPage(); $i++)
                        <?php
                            $half_total_links = floor($link_limit / 2);
                            $from = $results->currentPage() - $half_total_links;
                            $to = $results->currentPage() + $half_total_links;
                            if ($results->currentPage() < $half_total_links) {
                                $to += $half_total_links - $results->currentPage();
                            }
                            if ($results->lastPage() - $results->currentPage() < $half_total_links) {
                                $from -= $half_total_links - ($results->lastPage() - $results->currentPage()) - 1;
                            }
                        ?>
                        @if ($from < $i && $i < $to)
                            <li class="page-item {{ ($results->currentPage() == $i) ? ' active' : '' }}"><a class="page-link" href="javascript:void(0)" onclick="paginate('{{$i}}')" >{{ $i }}</a></li>
                        @endif
                    @endfor 
                    @if ($results->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" onclick="paginate('next')" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="javascript:void(0)" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>
        @endif
    </div>
</div> 
@endif
@endif
