<div class="d-flex justify-content-between align-items-center flex-wrap">
    <?php
        $link_limit = 6; 
    ?>
    @if ($results->lastPage() > 1)
        <div class="d-flex flex-wrap mr-3">
            @if ($results->onFirstPage()) 
                <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-arrow-back icon-xs"></i>
                </a>
            @else
                <a href="{{ $results->url(1)  }}" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-double-arrow-back icon-xs"></i>
                </a>
                <a href="{{ $results->previousPageUrl() }}" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-arrow-back icon-xs"></i>
                </a>
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
                    <a href='{{ $results->url("$i") }}' class="btn btn-icon btn-sm border-0 btn-hover-success mr-2 my-1 {{ ($results->currentPage() == $i) ? ' active' : '' }}">{{ $i }}</a>
                @endif
            @endfor
            @if ($results->hasMorePages())

                <a href="{{ $results->nextPageUrl() }}" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-arrow-next icon-xs"></i>
                </a>
                <a href="{{ $results->url($results->lastPage()) }}" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-double-arrow-next icon-xs"></i>
                </a> 
            @else
                <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-light-success mr-2 my-1">
                    <i class="ki ki-bold-arrow-next icon-xs"></i>
                </a>
            @endif
            <!-- <a href="javascript:void(0)" class="btn btn-icon btn-sm border-0 btn-hover-success mr-2 my-1">...</a> -->
            <!-- <a href="javascript:void(0)" class="btn btn-icon btn-sm border-0 btn-hover-success mr-2 my-1">...</a> -->
        </div>
    @endif
    <div class="d-flex align-items-center">
        {{ Form::select('per_page',[config::get("Reading.record_per_page") => trans('messages.global.default')] + Config::get('per_page'),((isset($searchVariable['per_page'])) ? $searchVariable['per_page'] : ''), ['class' => 'form-control form-control-sm text-success font-weight-bold mr-4 border-0 bg-light-success','onchange'=>'page_limit()','id'=>'per_page','style'=>"width: 95px;"]) }}
        <span class="text-muted">{{ trans('messages.global.showing')}} {{ $results->firstItem() }} {{ trans('messages.global.to')}} {{ $results->lastItem() }} {{ trans('messages.global.of')}} {{$results->total()}} {{ trans('messages.global.entries')}}</span>
    </div>
</div>