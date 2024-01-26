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
    <!-- <form action="/email/list" method="post">
        @csrf
        <div class="row mt-5">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Search BY Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ Session::get('title');}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Search BY Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ Session::get('email');}}">
                </div>
            </div>
            <div class="col-md-3 mt-4">
                <input type="submit" id="submit" value="Search" class="btn btn-primary mt-3">
                <input type="button" value="Reset" onclick="resetForm()" class="btn btn-danger mt-3">
            </div>
        </div>
                
    </form> -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="market-status-table mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="myTable">
                                <thead class="heading-td">
                                    <th class="">Sr#</th>
                                    <th class="">Keyword</th>
                                    <th class="">Title</th>
                                    <th class="">Email</th>
                                    <th class="">Email Sent</th>
                                    <!-- <th class="coin-name">Contact</th> -->
                                </thead>
                                @if(count($keyword->keyword_records) > 0)
                                @foreach($keyword->keyword_records as $data)
                                <tr class="text-end">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fixed-size-cell">{{ $keyword->name }}</td>
                                    <td style="width: 300px;">{!! wordwrap($data->title, 40,'<br>') !!}</td>
                                    <td class="fixed-size-cell">{{ $data->email }}</td>
                                    @php($contact = 'Contact us')
                                    @if($data->url == '')
                                    @php($contact = '--')
                                    @endif
                                    <!-- <td class="break-line" colspan="3"><a title="{{$data->url}}"
                                                        href="{{$data->url}}">{{ $contact }}</a></td> -->
                                    @if($data->email_sent == true)
                                    @php($email_sent = 'yes')
                                    @else
                                    @php($email_sent = 'no')
                                    @endif
                                    <td style="text-align:end;">{{ $email_sent }}</td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <p style="font-size:22px;">No Data</p>
                                    </td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection


    @section('script')
    <script>
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
    $(document).ready(function() {
        // $('#myTable').DataTable();
        let table = new DataTable('#myTable', {
            columns: [{
                    type: 'num',
                    targets: 0
                },// For Sr#
                null, // Keyword
                null, // Title
                null, // Email
                null // Contact
            ],
            order: [
                [0, 'asc']
            ],
            // Add other DataTable options as needed
        });
    });
    </script>
    @endsection