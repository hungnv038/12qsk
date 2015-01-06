<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-green">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> API </h3>
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
            data: {{json_encode($statisticLogs)}},
            // The name of the data record attribute that contains x-visitss.
            xkey: 'php_sapi_name',
            // A list of names of data record attributes that contain y-visitss.
            ykeys: ['avgTime'],
            // Labels for the ykeys -- will be displayed when you hover over the
            // chart.
            labels: ['API'],
            // Disables line smoothing
            smooth: false,
            resize: true
        });
    })
</script>