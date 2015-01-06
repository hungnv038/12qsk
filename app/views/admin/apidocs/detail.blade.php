<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            {{isset($apiData->method) ? $apiData->method : ''}} {{isset($apiData->name)?':'. $apiData->name:'No Api'}}
            <div class="" style="float: right">
                Version:
                {{Form::select("version", $availablesVersions, isset($version)?$version:'3.1', array('onchange'=>"getApiDoc(this,0,'detail');", 'id' => 'version'))}}
            </div>
        </h3>
    </div>
        <div class="panel-body">
        @if(isset($apiData->content['summary']))
            <div class="header"><h4 class="text-primary">Summary</h4></div>
            <p class="text-muted">{{$apiData->content['summary']}}</p>
        @endif

        @if(isset($apiData->content['fields']))
            <div class="header"><h4 class="text-primary">Parameters</h4></div>
            @foreach($apiData->content['fields'] as $field)
                <div class="row" style="border-bottom: 1px solid #eee;padding-top:10px;padding-bottom:10px;">
                    <div class="col-lg-3">
                        <div><strong>{{$field['name']}}</strong></div>
                        @if($field['require'] == 'require')
                            <div class="text-danger small">{{$field['require']}} ({{$field['type']}})</div>
                        @else
                            <div class="text-muted small">{{$field['require']}} ({{$field['type']}})</div>
                        @endif
                    </div>
                    <div class="col-lg-9">
                        <p class="text-muted">{{$field['description']}}</p>
                    </div>
                </div>
            @endforeach
        @endif

        @if(isset($apiData->content['output']))
            <script>
                $(document).ready(function() {
                    var node = new PrettyJSON.view.Node({
                        el: '#output',
                        data: <?php echo $apiData->content['output'] ?>,
                        dateFormat:"DD/MM/YYYY - HH24:MI:SS"
                    });
                });
            </script>
            <div class="header"><h4 class="text-primary">Outputs</h4></div>
            <pre>
                <div id="output"></div>
            </pre>
        @endif
    </div>
</div>
