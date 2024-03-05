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
    @if ($errors->has('campaign_id'))
    <div class="alert alert-danger mt-5">
        {{ $errors->first('campaign_id') }}
    </div>
    @endif

    {{-- <div class="row mt-5">
        <div class="col-md-12 p-0">
            <button type="button" class="btn btn-primary float-right mr-3" data-toggle="modal"
                data-target="#addCampaignModal">Add
                Campaign ID</button>
        </div>
    </div> --}}
    <div class="row mt-5">
        <div class="col-12">
            <h3>Campaign ID</h3>
        </div>
    </div>
    <div class="row table-section mt-3 mb-5">
        <div class="col-12 p-0">
            <div class="card">
                <div class="card-body">
                    <div class="market-status-table mt-4">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <tr class="bg-info text-uppercase text-white thead">
                                    <td>Sr#</td>
                                    <td>Campaign ID</td>
                                    <td>Created date</td>
                                    <td>Updated date</td>
                                    <td>Action</td>
                                </tr>
                                @if(count($campaigns) > 0)
                                @foreach($campaigns as $data)
                                <tr class="text-end">
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle">{{ $data->campaign_id }}</td>                               
                                    <td class="align-middle"><span class="label label-success">{{ $data->created_at->format('Y-m-d H:i:s') }}</span></td>
                                    <td class="align-middle"><span class="label label-success">{{ $data->updated_at->format('Y-m-d H:i:s') }}</span></td>
                                    <td>
                                        {{-- <i class="ti ti-delete"></i> --}}
                                        <i class="fa fa-edit" style="font-size: larger;" onclick="editCampaignId('{{ $data->id }}' , '{{ $data->campaign_id }}')"></i>
                                    </td>
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
                                    <td></td>

                                </tr>
                                @endif
                            </table>
                            <div class="mt-4">
                                {!! $campaigns->links() !!}
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
<div class="modal fade" id="addCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Campaign ID</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('add_campaign_id') }}" method="post" id="campaignForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="campaign_id">Campaign ID</label>
                        <input type="text" id="campaign_id" name="campaign_id" class="form-control"
                            placeholder="Enter Campaign ID" style="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">Add Campaign ID</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end modal -->

<!-- update Modal -->
<div class="modal fade" id="updateCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top:5%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Campaign ID</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('campaign_id_update') }}" method="post" id="campaignForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="updateCampaignId">Campaign ID</label>
                        <input type="hidden" name="id" id="id" value="">
                        <input type="text" id="updateCampaignId" name="campaign_id" class="form-control"
                            placeholder="Enter Campaign ID" value="" style="" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end update modal -->
@endsection

@section('script')

<script>

$(document).ready(function(){

});

function showSuccessMessage(message) {
    swal.fire('Success', message, 'success');
}

function editCampaignId(id , campaign_id)
{
    $('#id').val(id);
    $('#updateCampaignId').val(campaign_id);
    $('#updateCampaignModal').modal('show');
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

</script>
@endsection