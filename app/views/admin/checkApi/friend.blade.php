@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'getFriend', 'method' => 'GET')) }}
                    {{Form::label('access_token', 'access_token: ')}}
                    {{Form::text('access_token', '9cd82adcc7376c998c54c0868e857abc')}}
                    {{Form::label('service_app', 'service_app: ')}}
                    {{Form::text('service_app', 'picchat')}}
                    {{Form::label('limit', 'limit: ')}}
                    {{Form::text('limit', '10')}}
                    {{Form::submit('Get Friends', array('class' => 'btn btn-xs btn-primary', 'onclick' => 'getFriends(); return false;'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
	var urlApi = '<?php echo URL::to('/') ?>/';
	function getUser(access_token, service_app) {
        $.ajax({
            url: urlApi + 'getFriend',
            data: 'access_token='+ access_token +'&service_app='+ service_app,
            dataType: 'json',
            type: "GET",
            beforeSend: function() {
                $('#information').html('Loading...');
            },
            success:function(result) {
                console.log(result);
            },
            error: function(jqXHR){
                $('#information').html(jqXHR.responseText);
            }
        });
	}
	function getFriends() {
	    var access_token = $('#access_token').val();
	    var service_app = $('#service_app').val();
	    var limit = $('#limit').val();
	    getUser(access_token, service_app);
	    $.ajax({
            url: urlApi + 'friends',
            data: 'access_token='+ access_token +'&service_app='+ service_app +'&limit='+ limit,
            dataType: 'json',
            type: "GET",
            beforeSend: function() {
                $('#result').html('Loading...');
            },
            success:function(result) {
                console.log(result);
                var strHeader = '';
                var classType = 'btn-success';
                for(i=0;i<result.objects.length;i++){
                    strHeader += '<div ><h3>'+ result.objects[i].type +' - '+ result.objects[i].name +'</h3></div>';
                    if(result.objects[i].items == null) {
                    } else {
                        var items = result.objects[i].items;
                        if(items.length > 0) {
                            var strHtml = '<table class="table table-hover">';
                            for(j=0;j<items.length;j++) {
                                var fileAvatar = items[j]['avatar'];
                                var message_count = items[j].message_count;
                                strHtml += 	'<tr>';
                                strHtml += 		'<td style="width:10%;text-align:left;"><img src="'+ fileAvatar +'" class="margin" height="50"/></td>';
                                strHtml += 		'<td style="width:10%;text-align:left;">'+ items[j].name +'</td>';
                                strHtml += 		'<td style="width:5%;text-align:right;"><span class="btn btn-default">'+ items[j].type +'</span></td>';
                                strHtml += 		'<td style="width:5%;text-align:right;"><span class="btn '+ classType +'">'+ message_count +'</span></td>';

                                strHtml += 	'</tr>';
                            }
                            strHtml += '</table>';
                            strHeader += strHtml;
                        }
                    }
                }

                $('#result').html(strHeader);
            },
            error: function(jqXHR){
                $('#result').html(jqXHR.responseText);
            }
        });
	}
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
				<div id="information" class="box-body table-responsive no-padding"></div>
				<div id="result" class="box-body table-responsive no-padding"></div>
			</div>
		</div>
	</div>
</div>
@stop