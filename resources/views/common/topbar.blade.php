<nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="#"> 
                      <span class="hidden-xs">
                        <img src="/images/admin-text-dark.png" alt="home" class="light-logo">
                     </span> </a>
                </div>
                <!-- /Logo -->
                <ul class="nav navbar-top-links navbar-right pull-right active">
                    <li class="in">
                        <form role="search" class="app-search hidden-sm hidden-xs m-r-10">
                            <input type="text" placeholder="Search..." class="form-control"> <a href="" class="active"><i class="fa fa-search"></i></a> </form>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                            <img src="{{Auth::user()->logo ? Auth::user()->logo : '/images/user_default.png'}}" alt="user-img" width="36" class="img-circle">
                            <b class="hidden-xs">{{Auth::user()->name}}</b>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                        <img src="{{Auth::user()->logo ? Auth::user()->logo : '/images/user_default.png'}}" alt="user">
                                    </div>
                                    <div class="u-text p-l-0">
                                        <h4>{{Auth::user()->name}}</h4>
                                        <p class="text-muted">{{Auth::user()->getUserType()}}</p>
                                        @if (Auth::user()->isVnedorOrDistributor())
                                            <a href="/account/transaction/onlinepay" class="btn btn-rounded btn-danger btn-sm">充值</a>
                                        @endif
                                        
                                    </div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                            <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="/logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a></li>
                            <form id="logout-form" action="/logout" method="POST" style="display: none;">
                                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            </form>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
</nav>