<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="assets/images/icon/favicon.ico">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('assets/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="assets/css/slicknav.min.css">
    <!-- amchart css -->
    <link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css"
        media="all" />
    <!-- others css -->
    <link rel="stylesheet" href="{{ asset('assets/css/typography.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/default-css.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css')}}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- modernizr css -->
    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3.min.js')}}"></script>
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
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- preloader area start -->
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <!-- preloader area end -->
    <!-- page container area start -->
    <div class="page-container">
        <!-- sidebar menu area start -->
        <div class="sidebar-menu">
            <div class="sidebar-header">
                <div class="logo">
                    <a href="/"><img src="{{ asset('assets/images/icon/logo.png')}}" alt="logo"></a>
                </div>
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                    <nav>
                        <ul class="metismenu" id="menu">
                            <li class="{{ request()->is('/') ? 'active' : '' }}">
                                <a href="/"><i class="ti-dashboard"></i><span>dashboard</span></a>
                            </li>
                            <li class="{{ request()->is('/email/list*') ? 'active' : '' }}">
                                <a href="/email/list"><i class="ti-email"></i><span>Email List</span></a>
                            </li>
                            <!-- <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="ti-pie-chart"></i><span>Charts</span></a>
                                <ul class="collapse">
                                    <li><a href="barchart.html">bar chart</a></li>
                                    <li><a href="linechart.html">line Chart</a></li>
                                    <li><a href="piechart.html">pie chart</a></li>
                                </ul>
                            </li> -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- sidebar menu area end -->
        <!-- main content area start -->
        <div class="main-content">
            <!-- header area start -->
            <div class="header-area">
                <div class="row align-items-center">
                    <!-- nav and search button -->
                    <div class="col-md-11 col-sm-11 clearfix">
                        <div class="nav-btn pull-left">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <div class="search-box pull-left" style="width: 90%;">
                            <form action="/add/keyword" method="post" id="keywordForm">
                                @csrf
                                <input type="text" id="keyword" name="keyword" placeholder="Enter a Keyword"
                                    style="width: 57%;" required="">
                                <button type="submit" class="btn btn-primary"
                                    style="width:9%!important;border-radius: 19px;">Add Keyword</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-1 col-sm-1 pull-right">
                        <!-- <form action="/logout" method="post">
                        <input type="hidden" name="_token" value="FKxz20wHpVV1t3ERwkA1VnrwGpdoQrDGeJKYwyDO">                        <input type="submit" class="btn btn-secondary btn-sm" value="Logout">
                    </form> -->
                        <a href="http://127.0.0.1:8000/logout" class="btn btn-danger btn-sm pull-right">Logout</a>
                    </div>
                </div>

            </div>
            <!-- header area end -->
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
                <div class="row">
                    <div class="col-md-5 mt-5">
                        <form action="/" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="">Search BY Title</label>
                                        <input type="text" class="form-control" id="searchFilter" name="search"
                                            value="{{ Session::get('keyword');}}" placeholder="Search Keyword">
                                    </div>
                                </div>
                                <div class="col-md-4 mt-5">
                                    <input type="submit" id="submit" value="Search" class="btn btn-primary">
                                    <input type="button" onclick="resetForm()" value="Reset" class="btn btn-danger">
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="col-md-6 offset-1 mt-5">
                        <form action="/export" method="post" id="search" class="mt-5">
                            @csrf
                            <!-- <input type="hidden" name="_token" value="H8HO4rRMYm3zs1u4HvOwthZ98bVx13CLVBJSL1Hu">-->
                            <div class="row">
                                <div class="col-md-5 offset-5">
                                    <select class="form-control p-2" name="keyword" id="export"
                                        style="height: fit-content;">
                                        <option value="" selected="">Select Keyword</option>
                                        @foreach($records as $record)
                                        <option value="{{ $record->id }}">{{ $record->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <input type="submit" class="btn btn-primary" value="Export">
                                </div>
                                <div class="col-md-1"></div>
                            </div>
                        </form>
                    </div>
                </div>


                <div class="row mt-5 mb-5">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="market-status-table mt-4">
                                    <div class="table-responsive">
                                        <table class="dbkit-table">
                                            <tr class="heading-td">
                                                <td class="mv-icon">Sr#</td>
                                                <td class="coin-name" style="margin-left: -7%">Keyword</td>
                                                <td class="coin-name" style="margin-right: 3%">Status</td>
                                                <td class="coin-name" style="margin-left: -2%">Action</td>
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
        </div>
        <!-- main content area end -->
        <!-- footer area start-->
        <footer>
            <div class="footer-area">
                <p>© Copyright 2018. All right reserved.
                    <!-- Template by <a href="#">Colorlib</a>.-->
                </p>
            </div>
        </footer>
        <!-- footer area end-->
    </div>
    <!-- page container area end -->
    <!-- offset area start -->
    <div class="offset-area">
        <div class="offset-close"><i class="ti-close"></i></div>
        <ul class="nav offset-menu-tab">
            <li><a class="active" data-toggle="tab" href="#activity">Activity</a></li>
            <li><a data-toggle="tab" href="#settings">Settings</a></li>
        </ul>
        <div class="offset-content tab-content">
            <div id="activity" class="tab-pane fade in show active">
                <div class="recent-activity">
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Added</h4>
                            <span class="time"><i class="ti-time"></i>7 Minutes Ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You missed you Password!</h4>
                            <span class="time"><i class="ti-time"></i>09:20 Am</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Member waiting for you Attention</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>You Added Kaji Patha few minutes ago</h4>
                            <span class="time"><i class="ti-time"></i>01 minutes ago</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg1">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Ratul Hamba sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Hello sir , where are you, i am egerly waiting for you.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg2">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="fa fa-bomb"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                    <div class="timeline-task">
                        <div class="icon bg3">
                            <i class="ti-signal"></i>
                        </div>
                        <div class="tm-title">
                            <h4>Rashed sent you an email</h4>
                            <span class="time"><i class="ti-time"></i>09:35</span>
                        </div>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse distinctio itaque at.
                        </p>
                    </div>
                </div>
            </div>
            <div id="settings" class="tab-pane fade">
                <div class="offset-settings">
                    <h4>General Settings</h4>
                    <div class="settings-list">
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch1" />
                                    <label for="switch1">Toggle</label>
                                </div>
                            </div>
                            <p>Keep it 'On' When you want to get all the notification.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show recent activity</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch2" />
                                    <label for="switch2">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show your emails</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch3" />
                                    <label for="switch3">Toggle</label>
                                </div>
                            </div>
                            <p>Show email so that easily find you.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Show Task statistics</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch4" />
                                    <label for="switch4">Toggle</label>
                                </div>
                            </div>
                            <p>The for attribute is necessary to bind our custom checkbox with the input.</p>
                        </div>
                        <div class="s-settings">
                            <div class="s-sw-title">
                                <h5>Notifications</h5>
                                <div class="s-swtich">
                                    <input type="checkbox" id="switch5" />
                                    <label for="switch5">Toggle</label>
                                </div>
                            </div>
                            <p>Use checkboxes when looking for yes or no answers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- offset area end -->
    <!-- jquery latest version -->
    <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <!-- bootstrap 4 js -->
    <script src="{{ asset('assets/js/popper.min.js')}}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('assets/js/metisMenu.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.slicknav.min.js')}}"></script>

    <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <!-- start zingchart js -->
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script>
    zingchart.MODULESDIR = "https://cdn.zingchart.com/modules/";
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "ee6b7db5b51705a13dc2339db3edaf6d"];
    </script>
    <!-- all line chart activation -->
    <script src="{{ asset('assets/js/line-chart.js')}}"></script>
    <!-- all pie chart -->
    <script src="{{ asset('assets/js/pie-chart.js')}}"></script>
    <!-- others plugins -->
    <script src="{{ asset('assets/js/plugins.js')}}"></script>
    <script src="{{ asset('assets/js/scripts.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

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

    function deleteKeyword(id) {

        swal.fire({
            title: 'Are you sure',
            text: 'You want to delete the keyword',
            confirmButtonText: 'Yes',
            showCancelButton: true,
            cancelButtonText: 'No',
        }).then(function(result) {

            if (result.value) {
                $.ajax({
                    url: '/delete/keyword/' + id,
                    type: 'get',
                    dataType: 'json',
                    data: {
                        id,
                        id
                    },
                    success: function(response) {
                        swal.fire({
                            title: 'success',
                            text: response.message,
                            type: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            location.reload();
                        });
                    },
                    error: function(response) {
                        console.log(response)
                        swal.fire('Error', 'Something went wrong', 'error')
                    }

                });

            }
        });

    }

    function resetForm() {
        $('#searchFilter').val('');
        $('#submit').click();
    }
    </script>
</body>

</html>