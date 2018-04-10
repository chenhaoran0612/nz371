<div class="navbar-default sidebar" role="navigation" style="overflow: visible;">

            <div class="slimScrollDiv" style="position: relative; overflow: visible; width: auto; height: 100%;"><div class="sidebar-nav slimscrollsidebar active" style="overflow-x: visible; overflow-y: hidden; width: auto; height: 100%;">
                <div class="sidebar-head"><h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3> </div>
                <div class="user-profile">
                    <div class="dropdown user-pro-body">
                        <div><img src="{{Auth::user()->logo ? Auth::user()->logo : '/images/user_default.png'  }}" alt="user-img" class="img-circle"></div>
                        <p class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->nick_name}}</p>
                        
                    </div>
                </div>
                <ul class="nav in" id="side-menu">
                    @foreach (Auth::user()->menuLoad() as $node)
                        <li> <a href="#" class="waves-effect"><i class="{{$node['setting']['i_class']}}" data-icon="v"></i> <span class="hide-menu"> {{$node['setting']['name']}} <span class="fa arrow"></span> </span></a>
                            <ul class="nav nav-second-level collapse" style="padding-left: 20px;">
                            @foreach ($node['subnodeArr'] as $subnode)
                              <li>
                                <li> <a href="{{$subnode['url']}}"><span class="hide-menu">{{$subnode['name']}}</span></a> </li>
                              </li>
                            @endforeach
                          </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>