@extends('layouts.outside-pc')
@section('content')
<body data-default-background-img="/imgs/bg5.jpg" data-overlay="true" data-overlay-opacity="0.35"><div class="vegas-overlay" style="margin: 0px; padding: 0px; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; background-size: contain; background-image: url(&quot;/imgs/background-image-overlay-full.png&quot;); opacity: 0.35;"></div><img class="vegas-background" src="/imgs/bg5.jpg" style="position: fixed; left: 0px; top: 0px; width: 2200px; height: 1200px; bottom: auto; right: auto;">   
    <!-- Outer Container -->
    <div id="outer-container">
      <!-- Left Sidebar -->
      <section id="left-sidebar">
        <div class="logo">
          <a href="#intro" class="link-scroll"><img src="/imgs/logo.png" alt="中牟二高"></a>
        </div>
        <div id="mobile-menu-icon" class="visible-xs" onclick="toggle_main_menu();"><span class="glyphicon glyphicon-th"></span></div>

        <ul id="main-menu">
          @foreach($categories as $category)
            <li class="menu-item scroll"><a href="#{{$category->id}}">{{$category->category_name}}</a></li>
          @endforeach
        </ul>
      </section>

      <section id="main-content" class="clearfix">
        <article id="intro" class="section-wrapper clearfix active" data-custom-background-img="/imgs/bg5.jpg">
          <div class="content-wrapper clearfix wow fadeInDown animated" data-wow-delay="0.3s" style="position: absolute; visibility: visible; animation-delay: 0.3s; animation-name: fadeInDown;">
            <div class="col-sm-10 col-md-9 pull-right">
                <section class="feature-text">
                  <h1>中牟二高</h1>
                  <p>欢迎来到中牟二高生涯规划 & 心理指导中心</p>
                </section>

            </div><!-- .col-sm-10 -->
          </div><!-- .content-wrapper -->
        </article><!-- .section-wrapper -->

        @foreach($categories as $category)
        <article id="{{$category->id}}" class="section-wrapper clearfix" data-custom-background-img="/imgs/bg3.jpg">
          <div class="content-wrapper clearfix" style="position: absolute;">
            <div class="col-sm-11 pull-right">
                <h1 class="section-title" style="text-align: left;">{{$category->category_name}}</h1>
                <section class="feature-columns row clearfix">
                    @foreach($category->article as $one)
                      <article class="feature-col col-md-3" onclick="openUrl({{$one['id']}})">
                          <div class="image-container">
                            <img data-img-src="{{$one['image'] ? $one['image'] : '/images/no_pic.jpg'}}" class="item-thumbnail" alt="imgs" src="{{$one['image'] ? $one['image'] : '/images/no_pic.jpg'}}" style="width: 100%">
                          </div>
                          <div class="caption">
                            <p style="text-align: left;">{{$one['title']}}</p>
                          </div>
                      </article>
                    @endforeach
                </section>
            </div><!-- .col-sm-10 -->
          </div><!-- .content-wrapper -->
        </article><!-- .section-wrapper -->
        @endforeach

      </section>
      <section id="footer">
        <div id="go-to-top" onclick="scroll_to_top();" class=""><span class="icon glyphicon glyphicon-chevron-up"></span></div>
        <div class="footer-text-line">© 2018 ChenHaoRan | Tec</div>
      </section> 

    </div>
    <div class="modal fade" id="common-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <div class="modal-body clearfix">
          </div><!-- .modal-body -->
        </div><!-- .modal-content -->
      </div><!-- .modal-dialog -->
    </div><!-- .modal -->    


</body>

@endsection
@section('extend_js')
<script type="text/javascript">
$('.carousel-inner').carousel('cycle');
function openUrl(id){
   window.location.href = '/article/view/' + id ;
}
</script>
@endsection