<style>
.btn-primary {
    background-color: #0088ff !important;
}
</style>

<div class="header-area">
    <div class="row align-items-center">
        <!-- nav and search button -->
  
        <div class="col-md-12 col-sm-12 pull-right">
            <!-- <form action="/logout" method="post">
                        <input type="hidden" name="_token" value="FKxz20wHpVV1t3ERwkA1VnrwGpdoQrDGeJKYwyDO">                        <input type="submit" class="btn btn-secondary btn-sm" value="Logout">
                    </form> -->
            <a href="logout" class="btn btn-danger btn-sm pull-right ml-2">Logout</a>
            <button type="button" class="btn btn-primary pull-right btn-sm ml-2" data-toggle="modal"
                data-target="#importCsv">Import Sent Email CSV</button>
            @if(isset($uri) && $uri == 'keyword/detail')
            <a href="{{ route('export' , ['keyword' => $keyword->id]) }}" class="btn btn-success pull-right btn-sm">Export
                Emails</a>
            @endif
        </div>
    </div>

</div>



<!-- Modal -->
<div class="modal fade" id="importCsv" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Import Sent Email CSV</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/import/csv" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="file" name="file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>