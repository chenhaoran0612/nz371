@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row bg-title" style="border-shadow:1px 0px 20px rgba(0, 0, 0, 0.08)">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">霍兰德测试报告</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                
            </div>
        </div>
        <div class="row">
            

            <div class="col-lg-6">
                <div class="white-box">
                    <h3 class="box-title">图形分析</h3>
                    <div>
                        <canvas id="chart" height="300" width="420" style="width: 423px; height: 320px;"></canvas>
                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="panel">
                    <div class="table-responsive">
                        <h3>测试报告解读</h3>
                        <p style="padding: 10px">测试结果 ： {{$holandTest->result}}</p>
                        <p style="padding: 10px">推荐职业 ： {{$holandTest->job_data}}</p>
                    </div>
                </div>
            </div>
            

            <div class="col-lg-6">
                <div class="panel">
                    <div class="table-responsive">
                        <h3>测试得分</h3>
                        <table class="table table-hover manage-u-table" style="font-size: 14px">
                            <thead>
                            <tr>
                                <th>R</th>
                                <th>C</th>
                                <th>E</th>
                                <th>S</th>
                                <th>A</th>
                                <th>I</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{$holandTest->detail->R}}</td>
                                <td>{{$holandTest->detail->C}}</td>
                                <td>{{$holandTest->detail->E}}</td>
                                <td>{{$holandTest->detail->S}}</td>
                                <td>{{$holandTest->detail->A}}</td>
                                <td>{{$holandTest->detail->I}}</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="panel">
                    <h1 style="text-align: center;font-weight: bold;padding: 20px">类型解析</h1>
                    <div class="table-responsive">
                        <div class="col-lg-6">
                            <h3>社会型(S)</h3>
                            <p><span style="color: red">共同特征</span>:喜欢与人交往、不断结交新的朋友、善言谈、愿意教导别人。关心社会问题、渴望发挥自己的社会作用。寻求广泛的人际关系，比较看重社会义务和社会道德</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢要求与人打交道的工作，能够不断结交新的朋友，从事提供信息、启迪、帮助、培训、开发或治疗等事务，并具备相应能力。如: 教育工作者(教师、教育行政人员)，社会工作者(咨询人员、公关人员)。</p>
                        </div>
                        <div class="col-lg-6">
                            <h3>企业型(E)</h3>
                            <p><span style="color: red">共同特征</span>:喜欢与人交往、不断结交新的朋友、善言谈、愿意教导别人。关心社会问题、渴望发挥自己的社会作用。寻求广泛的人际关系，比较看重社会义务和社会道德</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢要求具备经营、管理、劝服、监督和领导才能，以实现机构、政治、社会及经济目标的工作，并具备相应的能力。如项目经理、销售人员，营销管理人员、政府官员、企业领导、法官、律师。</p>
                        </div>
                        <div class="col-lg-6">
                            <h3>常规型(C)</h3>
                            <p><span style="color: red">共同特征</span>:尊重权威和规章制度，喜欢按计划办事，细心、有条理，习惯接受他人的指挥和领导，自己不谋求领导职务。喜欢关注实际和细节情况，通常较为谨慎和保守，缺乏创造性，不喜欢冒险和竞争，富有自我牺牲精神。</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢要求注意细节、精确度、有系统有条理，具有记录、归档、据特定要求或程序组织数据和文字信息的职业，并具备相应能力。如:秘书、办公室人员、记事员、会计、行政助理、图书馆管理员、出纳员、打字员、投资分析员。</p>
                        </div>
                        <div class="col-lg-6">
                            <h3>现实型(R)</h3>
                            <p><span style="color: red">共同特征</span>:愿意使用工具从事操作性工作，动手能力强，做事手脚灵活，动作协调。偏好于具体任务，不善言辞，做事保守，较为谦虚。缺乏社交能力，通常喜欢独立做事。</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢使用工具、机器，需要基本操作技能的工作。对要求具备机械方面才能、体力或从事与物件、机器、工具、运动器材、植物、动物相关的职业有兴趣，并具备相应能力。如:技术性职业(计算机硬件人员、摄影师、制图员、机械装配工)，技能性职业(木匠、厨师、技工、修理工、农民、一般劳动)。</p>
                        </div>
                        <div class="col-lg-6">
                            <h3>探索型(I)</h3>
                            <p><span style="color: red">共同特征</span>:思想家而非实干家,抽象思维能力强，求知欲强，肯动脑，善思考，不愿动手。喜欢独立的和富有创造性的工作。知识渊博，有学识才能，不善于领导他人。考虑问题理性，做事喜欢精确，喜欢逻辑分析和推理，不断探讨未知的领域。</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢智力的、抽象的、分析的、独立的定向任务，要求具备智力或分析才能，并将其用于观察、估测、衡量、形成理论、最终解决问题的工作，并具备相应的能力。 如科学研究人员、教师、工程师、电脑编程人员、医生、系统分析员。</p>
                        </div>
                        <div class="col-lg-6">
                            <h3>艺术型(A)</h3>
                            <p><span style="color: red">共同特征</span>:有创造力，乐于创造新颖、与众不同的成果，渴望表现自己的个性，实现自身的价值。做事理想化，追求完美，不重实际。具有一定的艺术才能和个性。善于表达、怀旧、心态较为复杂。</p>
                            <p><span style="color: rgba(44,171,227,0.8)">典型职业</span>:喜欢的工作具备艺术修养、创造力、表达能力和直觉，并将其用于语言、行为、声音、颜色和形式的审美、思索和感受，具备相应的能力。不善于事务性工作。如艺术方面(演员、导演、艺术设计师、雕刻家、建筑师、摄影家、广告制作人)，音乐方面(歌唱家、作曲家、乐队指挥)，文学方面(小说家、诗人、剧作家)。</p>
                        </div>

                    </div>
                </div>
            </div>
            

            <div class="col-lg-12">
                <div class="panel">
                    <h1 style="text-align: center;font-weight: bold;padding: 20px">附录 I</h1>
                    <div class="table-responsive">
                        
                        <table class="table" style="font-size: 14px">
                            <thead>
                            <tr>
                                <th>测试结果</th>
                                <th style="width: 800px">适用职业</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($resultMaps as $key => $value)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td style="width: 800px">{{$value}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>
    </div>
  @endsection
  
@section('extend_js')
<script type="text/javascript" src="/js/chart.js"></script>
<script type="text/javascript">

    var ctx = document.getElementById("chart").getContext("2d");
    var data = {
        labels: ["现实型(R)","探索型(I)", "艺术型(A)","社会型(S)", "企业型(E)", "常规型(C)"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "rgba(44,171,227,0.8)",
                strokeColor: "rgba(44,171,227,1)",
                pointColor: "rgba(44,171,227,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(19,218,254,1)",
                data: [10, 10, 10, 10, 10, 10]
            },
            {
                label: "My Second dataset",
                fillColor: "rgba(255,118,118,0.8)",
                strokeColor: "rgba(255,118,118,1)",
                pointColor: "rgba(255,118,118,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(255,118,118,1)",
                data: [{{$holandTest->detail->R}}, {{$holandTest->detail->I}}, {{$holandTest->detail->A}}, {{$holandTest->detail->S}}, {{$holandTest->detail->E}}, {{$holandTest->detail->C}}]
            }
        ]
    };
    var myRadarChart = new Chart(ctx).Radar(data, {
            scaleShowLine : true,
            angleShowLineOut : true,
            scaleShowLabels : false,
            scaleBeginAtZero : true,
            angleLineColor : "rgba(0,0,0,.1)",
            angleLineWidth : 1,
            pointLabelFontFamily : "'Arial'",
            pointLabelFontStyle : "normal",
            pointLabelFontSize : 10,
            pointLabelFontColor : "#666",
            pointDot : true,
            pointDotRadius : 3,
            tooltipCornerRadius: 2,
            pointDotStrokeWidth : 1,
            pointHitDetectionRadius : 20,
            datasetStroke : true,
            datasetStrokeWidth : 2,
            datasetFill : true,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            responsive: true
    });

</script>
@endsection