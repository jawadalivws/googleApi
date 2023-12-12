<div class="header-area">
    <div class="row align-items-center">
        <!-- nav and search button -->
        <div class="col-md-12 col-sm-12 clearfix">
            <div class="nav-btn pull-left">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="search-box pull-left" style="width:90%;">
                <form action="/add/keyword" method="post" id="keywordForm">
                    @csrf
                    <input type="text" id="keyword" name="keyword" placeholder="Enter a Keyword" style="width: 57%;"
                        required>
                    <input type="submit" class="btn submit" value="Add Keyword" style="width: 9%!important;">
                </form>
            </div>
        </div>
    </div>
    <div class="pull-right" style="margin-top:-2%;">
        <form action="/logout" method="post">
            @csrf
            <input type="submit" class="btn btn-secondary btn-sm" value="Logout">
        </form>
    </div>
</div>