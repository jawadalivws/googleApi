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
    .table>tbody>tr>td{
    padding: 5px!important;
    line-height: 2.1!important;
    border-top: 1px solid #ddd;
}
    </style>
</head>

<body>
@section('content')
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
                <a href="{{ route('export')}}" class="btn btn-success pull-right mr-3">Export All Emails</a>
                <!-- <button type="button" class="btn btn-primary float-right mr-3" data-toggle="modal" data-target="#addKeywordModal">Add
                    Keyword</button> -->
            </div>
        </div>
    <div class="row filter-section mb-5" style="display: none;">
        <div class="col-md-12">
        <form action="/email/list" method="post">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <h3 class="" style="">Search Filters</h3>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Search BY Keyword</label>
                    <select name="search_keyword" id="search_keyword" class="form-control"
                        style="height: fit-content;">
                        <option value="" selected>Select Keyword</option>
                        @foreach($keywords as $keyword)
                        <option value="{{ $keyword->id }}" @if($keyword->id ==
                            Session::get('search_keyword')) selected @endif>{{ $keyword->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Created Date From</label>
                    <input type="date" class="form-control" id="createdFrom" name="createdFrom"
                        value="{{ Session::get('createdFrom');}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Created Date To</label>
                    <input type="date" class="form-control" id="createdTo" name="createdTo"
                        value="{{ Session::get('createdTo');}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Search BY Title</label>
                    <input type="text" class="form-control" id="title" name="title"
                        value="{{ Session::get('title');}}" placeholder="Search by Title">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Search BY Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                        value="{{ Session::get('email');}}" placeholder="Search Email">
                </div>
            </div>
            <div class="col-md-12 mt-4">
                <input type="submit" id="submit" value="Search" class="btn btn-primary mt-3">
                <!-- <input type="button" value="Reset" onclick="resetForm()" class="btn btn-danger mt-3"> -->
                <a href="/email/list" class="btn btn-danger mt-3 text-white">Reset</a>
            </div>
        </div>

    </form>
        </div>
    </div>
    <!-- <div class="row">
        <div class="col-md-12">
        <h3 class="p-3" style="">All Emails</h3>
        </div>
    </div> -->
    <div class="row table-section mt-5 mb-5">
        <!-- <h3 class="" style="">All Emails</h3> -->
        <div class="col-12 p-0">
            <div class="card">
                <div class="card-body">
                    <div class="market-status-table mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <tr class="bg-info text-uppercase text-white thead">
                                    <td>Sr#</td>
                                    <td>Keyword</td>
                                    <td>Title</td>
                                    <td>Email</td>
                                    <td>Compain ID</td>
                                    <td>Created date</td>
                                    <!-- <td class="coin-name">Contact</td> -->
                                </tr>
                                @if(count($email_list) > 0)
                                @foreach($email_list as $data)
                                <tr class="text-end">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $data->keywords->name }}</td>
                                    <td class="align-middle">{!! wordwrap($data->title, 40,'<br>') !!}</td>
                                    <!-- https://mail.google.com/mail/u/0/?tab=rm&ogbl#inbox?compose='new -->
                                    <td class="align-middle" class="fixed-size-cell">{{ $data->email }}</td>
                                    <td class="align-middle">{{ getCompain()[$keyword->compain_id]}}</td>                                
                                    <td class="align-middle"><span class="label label-success">{{ getTimeAgo($data->created_at) }}</span></td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <p style="font-size:22px;">No Data</p>
                                </tr>
                                @endif
                            </table>
                            <div class="mt-4">
                                {!! $email_list->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

<script>

$(document).ready(function(){

<?php if(session()->get('search_keyword') || session()->get('createdFrom') || session()->get('createdTo') || session()->get('title') || session()->get('email')){ ?>
     $('.filter-section').toggle();
<?php  } ?>
});

function showSuccessMessage(message) {
    swal.fire('Success', message, 'success');
}

function searchWord() {
    var formData = $("#keywordForm").serialize();
    $.ajax({
        url: 'add/keyword',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.success) {
                // swal.fire('success' , response.message);
                swal.fire({
                    title: 'success',
                    text: response.message,
                    type: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    location.reload();
                });
            }
        },
        error: function(errors) {
            console.log(errors);
            // swal.fire('Error' , errors);
        }
    });
}

function resetForm() {
    $('#title').val('');
    $('#email').val('');
    $('#search_keyword').val('');
    $('#submit').click();
}

function toggleFilter(){
        $('.filter-section').toggle();
        if($('#toggle_filter').text() == 'Show Filters'){
            $('#toggle_filter').text('Hide Filters');
        }else{
            $('#toggle_filter').text('Show Filters');
        }
        console.log(this);
    }
</script>
@endsection