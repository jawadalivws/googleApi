@extends('layouts.layout')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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

.filter-section {
    background: white !important;
    margin-top: 2% !important;
    padding-bottom: 2% !important;
    border-radius: 10px;
}

.table-section {
    border-radius: 10px;
}

.form-control {
    background-color: whitesmoke !important;
}

.h-full {
    height: 63% !important;
}
.table.dbkit-table tr{
    height:50px;
}
.thead{
    color: white!important;
    font-weight: 600!important;
}
.table>tbody>tr>td{
    padding: 10px!important;
    line-height: 2.1!important;
    border-top: 1px solid #ddd;
}

.select2{
    width:100%!important;
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
    @if ($errors->has('campaign_id'))
    <div class="alert alert-danger mt-5">
        {{ $errors->first('campaign_id') }}
    </div>
    @endif

    <!-- chart section -->
    <div class="row mt-5">
        <!-- seo fact area start -->
        <div class="col-lg-6">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="card">
                        <div class="seo-fact sbg1">
                            <div class="chartjs-size-monitor"
                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="ti-email"></i> Total Email</div>
                                <h2>{{ $total_email }}</h2>
                            </div>
                            <!-- <canvas id="seolinechart1" height="83" style="display: block; width: 501px; height: 83px;" width="501" class="chartjs-render-monitor"></canvas> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-md-2 mb-3">
                    <div class="card">
                        <div class="seo-fact sbg2">
                            <div class="chartjs-size-monitor"
                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
                                <div class="seofct-icon"><i class="ti-share"></i> Sent Email</div>
                                <h2>{{ $email_sent }}</h2>
                            </div>
                            <!-- <canvas id="seolinechart2" height="83" width="501" style="display: block; width: 501px; height: 83px;" class="chartjs-render-monitor"></canvas> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-md-2">
                    <div class="card">
                        <div class="seo-fact sbg3">
                            <div class="chartjs-size-monitor"
                                style="position: absolute; inset: 0px; overflow: hidden; pointer-events: none; visibility: hidden; z-index: -1;">
                                <div class="chartjs-size-monitor-expand"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:1000000px;height:1000000px;left:0;top:0"></div>
                                </div>
                                <div class="chartjs-size-monitor-shrink"
                                    style="position:absolute;left:0;top:0;right:0;bottom:0;overflow:hidden;pointer-events:none;visibility:hidden;z-index:-1;">
                                    <div style="position:absolute;width:200%;height:200%;left:0; top:0"></div>
                                </div>
                            </div>
                            <div class="p-3 d-flex justify-content-between align-items-center">
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
            <div class="card h-full" style="">
                <div class="card-body">
                    <h4 class="header-title mb-0">Email</h4>
                    <div id="coin_distribution" style="height:56%!important;"></div>
                </div>
            </div>
        </div>
        <!-- Advertising area end -->
    </div>
    <!-- chart section end -->

    <!-- filters -->
    <div class="row mb-4" style="margin-top:-7.5%;">
        <div class="col-md-12 p-0">
            <button class="btn btn-info float-right" id="toggle_filter" onclick="toggleFilter()">Show Filters</button>
            <button type="button" class="btn btn-primary float-right mr-3" data-toggle="modal"
                data-target="#addKeywordModal">Add
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
                            <label for="">Search BY Campaign</label>
                            <input type="text" class="form-control" id="searchFilter" name="searchCampaign"
                                value="{{ Session::get('searchCampaign');}}" placeholder="Search Campaign">
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

    </div>

    <!-- filters end -->

    <div class="row table-section">
        <div class="col-12 p-0">
            <div class="card">
                <div class="card-body">
                    <div class="market-status-table mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <tr class="bg-info text-uppercase text-white thead">
                                    <td class="">Sr#</td>
                                    <td class="">Keyword</td>
                                    <td class="">Status</td>
                                    <td class="">Campaign ID</td>
                                    <td class="">Created Date</td>
                                    <td class="">Action</td>
                                </tr>
                                @if(count($keywords) > 0)
                                @foreach($keywords as $keyword)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $keyword->name }}</td>
                                    @if(count($keyword->keyword_records) > 0)

                                    @php($class = 'label label-success')
                                    @php($scan = 'Scanned')

                                    @else

                                    @php($class = 'label label-danger')
                                    @php($scan = 'Unscanned')

                                    @endif
                                    <td class="align-middle">
                                        <span for="" class="{{ $class }}">{{ $scan }}</span>
                                    </td>
                                    <td class="align-middle">{{ $keyword->compain_id }}</td>
                                    <td class="align-middle"><span class="label label-success">{{ getTimeAgo($keyword->created_at) }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="#" onclick="editKeyword('{{ $keyword->name }}', '{{ $keyword->compain_id }}' , '{{ $keyword->id }}')"
                                            class="btn btn-primary btn-sm"><i class="ti-pencil"></i></a>
                                        <a href="#" onclick="deleteKeyword({{$keyword->id}})"
                                            class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
                                        <a href="/keyword/detail/{{$keyword->id}}" class="btn btn-info btn-sm"><i
                                                class="ti-eye"></i></a>
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
</div>
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="addKeywordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="#" method="post" id="keywordForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="form-control"
                            value="{{old('keyword')}}" placeholder="Enter a Keyword" style="" required="">
                    </div>
                    <div class="form-group">
                        <label for="keyword">Campaign ID</label>
                        <input type="text" id="campaign_id" name="campaign_id" class="form-control"
                        value="{{old('campaign_id')}}" placeholder="Enter Campaign ID" style="" required="">
                    </div>
                    <div class="country_section">
                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country[]" id="country" value="{{old('country[]')}}" onchange="loadStates(this)" class="form-select" multiple>
                                @foreach($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary" onclick="addKeyword(event)">Add Keyword</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal -->
<!-- Modal -->
<div class="modal fade" id="editKeywordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="margin-top: 22%;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Keyword</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/update/keyword" method="post" id="editForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="keyword">Keyword</label>
                        <input type="hidden" name="id" id="editid">
                        <input type="text" id="editkeyword" name="keyword" class="form-control"
                            value="{{old('keyword')}}" placeholder="Enter a Keyword" style="" required="">
                    </div>
                    <div class="form-group">
                        <label for="keyword">Campaign ID</label>
                        <input type="text" id="editcampaign_id" name="campaign_id" class="form-control"
                        value="{{old('campaign_id')}}" placeholder="Enter Campaign ID" style="" required="">
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">Update Keyword</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal -->

@endsection

@section('script')
@include('keyword_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    var country_array = [];
    var state_array = [];
    var city_array = [];
    $(document).ready(function(){
        localStorage.clear();
        $('#country').select2();
    })

    function addKeyword(event){
        event.preventDefault();
        var keyword = $('#keyword').val();
        var campaign_id = $('#campaign_id').val();
        var token = $("input[name=_token]").val();

        $.ajax({
            url: '/add/keyword',
            method:'post',
            data : {
                keyword:keyword ,
                campaign_id:campaign_id, 
                country_array:country_array,
                _token:token
            },
            success: function(response){
                console.log(response);
                if(response.success){

                    swal.fire('Success' , 'Keyword added successfully.' , 'success');
                    $('#addKeywordModal').modal('hide');
                    setTimeout(() => {
                        location.reload();
                    }, 600);
                }else{
                    console.log(response.messages);
                }
            },
            error:function(err){
                console.log(err);
            }
        });
    }

    function loadStates(obj){

        var storeValues = localStorage.getItem('values')
        var values = $('#country').val();
        if(storeValues){
            var storeArray = storeValues.split(",");
            storeArray.forEach(data => {
                if(values.includes(data)){
                    console.log('its ok');
                }else{
                    $('#state_section'+data).remove();
                    var indexToDelete = country_array.findIndex(country => country.id === Number(data));
                    console.log(indexToDelete);
                    country_array.splice(indexToDelete, 1);
                }
            })

        }
        localStorage.setItem('values' , values);

        values.forEach(element => {
            
            if(storeValues != null && storeValues.includes(element)){
                
            }else{
                country_array.push({id:Number(element) , states:[]});
                $.ajax({
                    url : '/load_states?country_id='+element,
                    method: 'get',
                    success:function(response){
                        if(response.success && response.data.length > 0){
                            // console.log(response.data);
                            var country = response.data[0].country;
                            // var states = response.data.map(state => ({id:state.id , country_id:state.country_id, cities:[]}));

                            var html = '';
                                html += '<div class="form-group p-3 border rounded border-info" id="state_section'+country.id+'">';
                                html += '<h4>'+country.name+'</h4>';
                                html += '<label>States</label>';
                                html += '<select name="state[]" value="{{old("state[]")}}" id="state'+country.id+'" class="form-select state" onchange="loadCities(this)" multiple>';
                            response.data.forEach(state => {
                                
                                    html += '<option value='+state.id+'>'+state.name+'</option>';
                                    
                            });
                            html += '</select>';
                            html += '</div>';
                        }

                        $('.country_section').append(html);
                        $('.state').select2();
                        console.log(country_array);

                    },
                    error:function(error){
                        console.log(error);
                    }
                });
                
            }
        });
    }

    function loadCities(obj){
        var storeValues = localStorage.getItem('state')
        var parent = obj.closest('.form-group');
        var objId = obj.getAttribute('id');
        var id = parent.getAttribute('id');
        var countryId = parent.getAttribute('id').replace('state_section' , '');
        var values = $('#'+objId).val();
        if(storeValues){
            var storeArray = storeValues.split(",");
            storeArray.forEach(data => {
                if(values.includes(data)){
                    console.log('its ok');
                }else{
                    $('city_section'+data).remove();
                }
            })
        }
        localStorage.setItem('state' , values);
        values.forEach(element => {

            if(storeValues != null && storeValues.includes(element)){
                
            }else{

                var countryIndex = country_array.findIndex(country => country.id === Number(countryId));
                if (countryIndex !== -1) {
                    // Push state to the corresponding country's states array
                    country_array[countryIndex].states.push({
                        id: element,
                        country_id: Number(countryId),
                        cities: []
                    });
                } else {
                    alert()
                    // If country not found, create new entry in country_array
                    country_array.push({
                        id: countryId,
                        states: [{
                            id: element,
                            country_id: Number(countryId),
                            cities: []
                        }]
                    });
                }

                $.ajax({
                    url : '/load_cities?state_id='+element,
                    method: 'get',
                    success:function(response){
                        if(response.success && response.data.length > 0){
                            // console.log(response.data);
                            var state = response.data[0].state;

                            // Find the corresponding country in country_array
                            
                            var html = '';
                                html += '<div class="form-group p-4 border border-secondary data-state="'+state.id+'" rounded mt-4" id="city_section'+state.id+'" data-country="'+state.country_id+'">';
                                html += '<h4>'+response.data[0].state.name+'</h4>';
                                html += '<label>City</label>';
                                html += '<select name="city[]" value="{{old("city[]")}}" id="city'+state.id+'" onchange="addCityToArray(this)"  class="form-select city" multiple>';
                            response.data.forEach(city => {
                                
                                    html += '<option value='+city.id+'>'+city.name+'</option>';
                                    
                            });
                            html += '</select>';
                            html += '</div>';
                        }

                        
                        $('#'+id).append(html);
                        $('.city').select2();

                        console.log('country array' , country_array);
                    },
                    error:function(error){
                        console.log(error);
                    }
                });
                
            }
        });
    }

    function addCityToArray(obj){
        var objId = obj.getAttribute('id');
        var values = $('#'+objId).val();

        var countryId = $('#'+objId).closest('.form-group').data('country');
        var stateId = objId.replace('city','');

        var cities = [];
        cities = values.map(value=>{
            return {
                id:value
            };
        })
        var countryIndex = country_array.findIndex(country => country.id === countryId);
        if(countryIndex !== -1){
            var stateIndex = country_array[countryIndex].states.findIndex(state => state.id == stateId)
                if(stateIndex !== -1){
                    country_array[countryIndex].states[stateIndex].cities = cities;
                
            }
        }
        console.log(country_array[countryIndex]);
        console.log(country_array[countryIndex].states);
    }
</script>
@endsection