@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'checkApi', 'method' => 'GET')) }}
                    {{Form::label('username', 'User Name: ')}}
                    {{Form::text('username', '')}}
                    {{Form::submit('Find User', array('class' => 'btn btn-xs btn-primary', 'onclick' => 'findUsers(); return false;'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
	var urlApi = '<?php echo URL::to('/') ?>/';
	function findUsers() {
	    var username = $('#username').val();
	    $.ajax({
            url: urlApi + 'getUser',
            data: 'username='+ username,
            dataType: 'json',
            type: "GET",
            beforeSend: function() {
                $('#result').html('Loading...');
            },
            success:function(result) {
                console.log(result);
                var strHtml = '<table class="table table-hover">';
                for(i=0;i<result.notifications.length;i++){
                    var fileId = 0;
                    var fileThumb = '';
                    var actorAvatar = '';
                    if(result.notifications[i].resource == null) {
                    } else {
                        actorAvatar = result.notifications[i].actor.avatar;
                        fileId = result.notifications[i].resource.file.id;
                        fileThumb = result.notifications[i].resource.file.thumbnail;
                        getUserOpenPhoto(result.notifications[i].resource.file.id, access_token,service_app)
                    }
                    strHtml += 	'<tr>';
                    strHtml += 		'<td style="width:10%;text-align:center;"><img src="'+ actorAvatar +'" class="margin" height="50"/></td>';
                    strHtml += 		'<td><h4>'+ result.notifications[i].content +'</h4><div class="opend_'+ fileId +'" style="background: #f5f5f5;margin-top:10px; padding-top:10px;">Loading...</div></td>';
                    strHtml += 		'<td style="width:5%;text-align:right;"><span class="badge bg-red">'+ result.notifications[i].type +'</span></td>';
                    strHtml += 		'<td style="width:5%;text-align:right;"><span class="badge bg-red">'+ result.notifications[i].number_user_open_photo +'</span></td>';
                    strHtml += 		'<td style="width:10%;text-align:center;"><img src="'+ fileThumb +'" class="margin" height="50"/></td>';
                    strHtml += 	'</tr>';
                }
                strHtml += '</table>';
                $('#result').html(strHtml);
            },
            error: function(jqXHR){
                $('#result').html(jqXHR.responseText);
            }
        });
	}
	function getUserOpenPhoto(photoId, access_token, service_app) {
		$.ajax({
			url: urlApi + 'files/'+ photoId +'/openedusers',
			data: 'access_token='+ access_token +'&service_app='+ service_app,
			dataType: 'json',
			type: "GET",
			beforeSend: function() {
				$('.opend_' + photoId).html('Loading...');
			},
			success:function(result) {
				var strHtml = '<div class="row" style="padding-bottom: 10px;">';
				var count = 0;
				for(i=0;i<result.users.length;i++){
				    if(count%3 == 0 && count > 1) strHtml += '<div class="row" style="padding-bottom: 10px;">';
					strHtml += '<div class="col-lg-1"><img src="'+ result.users[i].avatar +'" height="30"/></div>';
					strHtml += '<div class="col-lg-3">'+ result.users[i].name +'</div>';
					count++;
					if(count%3 == 0) strHtml += '</div>';
				}
				strHtml += '</div>';
				$('.opend_' + photoId).html(strHtml);
			},
			error: function(jqXHR){
			    $('.opend_' + photoId).html(jqXHR.responseText);
			}
		});
	}
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
				<div id="result" class="box-body table-responsive no-padding"></div>
			</div>
		</div>
	</div>
</div>
@stop