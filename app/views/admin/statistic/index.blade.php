@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$totalUser}}</div>
                        <div>Total Users</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @foreach($statisticLogs as $statisticLog)
        <?php 
            if($statisticLog->level == 'error') {
                $panelInfo = 'red';
                $msgLog = 'Total Errors';
            } elseif($statisticLog->level == 'info') {
                $panelInfo = 'green';
                $msgLog = 'Total Info';
            } else {
                $panelInfo = 'yellow';
                $msgLog = 'Total Warning';
            }
        ?>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-{{$panelInfo}}">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-bug fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge">{{$statisticLog->total}}</div>
                        <div>{{$msgLog}}</div>
                    </div>
                </div>
            </div>
            <a href="#">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    @endforeach
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Users register by days</h3>
            </div>
            <div class="panel-body">
                <div id="morris-area-chart"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        // Line Chart
        Morris.Bar({
            // ID of the element in which to draw the chart.
            element: 'morris-area-chart',
            // Chart data records -- each entry in this array corresponds to a point on
            // the chart.
            data: {{json_encode($statisticUsers)}},
            // The name of the data record attribute that contains x-visitss.
            xkey: 'created_at',
            // A list of names of data record attributes that contain y-visitss.
            ykeys: ['total_user'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['Register'],
            // Disables line smoothing
            smooth: false,
            resize: true
        });
    })
</script>
@stop