@extends('layouts.layout')
    <style>
    .break-line {
        word-wrap: break-word;
    }

    .ti-trash {
        /* color:red; */
    }

    .submit {
        width: 15% !important;
        background-color: #007bff !important;
        color: white;
        border-color: black;
    }

    .fixed-size-cell {
        width: 250px;
    }
    .filter-section{
        background: white!important;
        margin-top: 2%!important;
        padding-bottom: 2%!important;
        border-radius: 10px;
    }
    .table-section{
        border-radius: 10px;
    }
    .form-control{
        background-color:whitesmoke!important;
    }
    .h-full{
        height:69%!important;
    }
    </style>

    @section('content')
    <div class="main-content-inner">
        <!-- market value area start -->
        @if(session()->has('success'))
        <div class="alert alert-success mt-5">
            {{session()->get('success')}}
            <!-- <script>
                showSuccessMessage("{{ session()->get('success') }}")
            </script> -->
        </div>
        @endif
        @if ($errors->has('keyword'))
        <div class="alert alert-danger mt-5">
            {{ $errors->first('keyword') }}
        </div>
        @endif
        <div class="row mt-5 mb-5">
            <div class="col-md-12 p-0">
                <button class="btn btn-info float-right" id="toggle_filter" onclick="toggleFilter()">Show Filters</button>
                <button type="button" class="btn btn-primary float-right mr-3" data-toggle="modal" data-target="#addKeywordModal">Add
                    Keyword</button>
            </div>
        </div>
        <div class="row filter-section mb-5" style="display: none;">
            <div class="col-md-12 mt-5">
                <form action="/" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="header-title" style="">Search Filters</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Search BY Title</label>
                                <input type="text" class="form-control" id="searchFilter" name="searchKeyword"
                                    value="{{ Session::get('searchKeyword');}}" placeholder="Search Keyword">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Created From</label>
                                <input type="date" class="form-control" id="createdFrom" name="createdFrom"
                                    value="{{ Session::get('createdFrom');}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Created To</label>
                                <input type="date" class="form-control" id="createdTo" name="createdTo"
                                    value="{{ Session::get('createdTo');}}">
                            </div>
                        </div>
                        <div class="col-md-3 mt-4" style="margin-top: 1.6%!important;">
                            <input type="submit" id="submit" value="Search" class="btn btn-primary">
                            <!-- <input type="button" onclick="resetForm()" value="Reset" class="btn btn-danger"> -->
                            <a href="/" class="btn btn-danger">Reset</a>
                        </div>
                    </div>

                </form>
            </div>
            <!-- <div class="col-md-6 offset-1 mt-5">
                <form action="/export" method="post" id="search" class="mt-5">
                    @csrf
                    <div class="row">
                        <div class="col-md-5 offset-5">
                            <select class="form-control p-2" name="keyword" id="export"
                                style="height: fit-content;">
                                <option value="" selected="">Select Keyword</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <input type="submit" class="btn btn-primary" value="Export">
                        </div>
                        <div class="col-md-1"></div>
                    </div>
                </form>
            </div> -->
        </div>

        <!-- chart section -->
        <div class="row mb-5">
                    <!-- seo fact area start -->
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card">
                                    <div class="seo-fact sbg1"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon"><i class="ti-email"></i> Total Email</div>
                                            <h2>{{ $total_email }}</h2>
                                        </div>
                                        <!-- <canvas id="seolinechart1" height="83" style="display: block; width: 501px; height: 83px;" width="501" class="chartjs-render-monitor"></canvas> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-md-2 mb-3">
                                <div class="card">
                                    <div class="seo-fact sbg2"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon"><i class="ti-share"></i> Sent Email</div>
                                            <h2>{{ $email_sent }}</h2>
                                        </div>
                                        <!-- <canvas id="seolinechart2" height="83" width="501" style="display: block; width: 501px; height: 83px;" class="chartjs-render-monitor"></canvas> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-md-2">
                                <div class="card">
                                    <div class="seo-fact sbg3"><div class="chartjs-size-monitor" style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;"><div class="chartjs-size-monitor-expand" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div></div><div class="chartjs-size-monitor-shrink" style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;"><div style="position:absolute;width:200%;height:200%;left:0; top:0"></div></div></div>
                                        <div class="p-4 d-flex justify-content-between align-items-center">
                                            <div class="seofct-icon"><i class="ti-share"></i> Pending Email</div>
                                            <h2>{{ $pending_email }}</h2>
                                        </div>
                                        <!-- <canvas id="seolinechart6" height="83" width="501" style="display: block; width: 501px; height: 83px;" class="chartjs-render-monitor"></canvas> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- seo fact area end -->
                    <!-- Advertising area start -->
                    <div class="col-md-6 coin-distribution">
                        <div class="card h-full" style="border-bottom:1px solid black;">
                            <div class="card-body">
                                <h4 class="header-title mb-0">Email</h4>
                                <div id="coin_distribution" style="height:63%!important;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Advertising area end -->
                </div>
        <!-- chart section end -->
        <div class="row table-section">
            <div class="col-12 p-0">
                <div class="card">
                    <div class="card-body">
                        <div class="market-status-table mt-4">
                            <div class="table-responsive">
                                <table class="dbkit-table">
                                    <tr class="heading-td">
                                        <td class="mv-icon">Sr#</td>
                                        <td class="coin-name" style="margin-left: -7%">Keyword</td>
                                        <td class="coin-name" style="margin-right: 0%">Status</td>
                                        <td class="coin-name" style="margin-right: -7%">Created Date</td>
                                        <td class="coin-name" style="margin-left: 0%">Action</td>
                                    </tr>
                                    @if(count($keywords) > 0)
                                    @foreach($keywords as $keyword)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="fixed-size-cell">{{ $keyword->name }}</td>
                                        @if(count($keyword->keyword_records) > 0)

                                        @php($class = 'label label-success')
                                        @php($scan = 'Scanned')

                                        @else

                                        @php($class = 'label label-danger')
                                        @php($scan = 'Unscanned')

                                        @endif
                                        <td class="fixed-size-cell"><span for=""
                                                class="{{ $class }}">{{ $scan }}</span></td>
                                        <td><span class="label label-success">{{ $keyword->created_at }}</span>
                                        </td>
                                        <td>
                                            <a href="#" onclick="deleteKeyword({{$keyword->id}})"
                                                class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
                                            <a href="/keyword/detail/{{$keyword->id}}"
                                                class="btn btn-info btn-sm"><i class="ti-eye"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr class="text-center">
                                        <p style="font-size:22px;">No Data</p>
                                    </tr>
                                    @endif
                                </table>
                                <div class="mt-4">
                                    {!! $keywords->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- market value area end -->
    </div>
    @endsection

    @section('modal')
    <!-- Modal -->
    <div class="modal fade" id="addKeywordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Keyword</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="/add/keyword" method="post" id="keywordForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="keyword" name="keyword" class="form-control"
                                placeholder="Enter a Keyword" style="" required="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                        <button type="submit" class="btn btn-primary">Add Keyword</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal -->
    @endsection

    @section('script')
    @include('keyword_script')  
    @endsection