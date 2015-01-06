@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'getNotification', 'method' => 'GET')) }}
                    <div class="row">
                        <div class="col-lg-4">{{Form::label('access_token', 'access_token: ')}}</div>
                        <div class="col-lg-6">{{Form::text('access_token', '6812c6a17e76b8b954b8bfc7e912f020')}}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">{{Form::label('service_app', 'service_app: ')}}</div>
                        <div class="col-lg-6">{{Form::text('service_app', 'picchat')}}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">{{Form::label('limit', 'limit: ')}}</div>
                        <div class="col-lg-6">{{Form::text('limit', '10')}}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4">{{Form::label('version', 'Version: ')}}</div>
                        <div class="col-lg-6">{{Form::select('version', array('3.01' => '3.01','3.1' => '3.1'), '3.1')}}</div>
                    </div>
                    {{Form::submit('Get Notification', array('class' => 'btn btn-xs btn-primary', 'onclick' => 'getNotifications(); return false;'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-lg-6"><div id="information" class="box-body table-responsive no-padding"></div></div>
</div>
<script>
	var urlApi = '<?php echo URL::to('/') ?>/';
	function getUser(access_token, service_app) {
        $.ajax({
            url: urlApi + 'postMessage',
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
	function getNotifications() {
	    var access_token = $('#access_token').val();
	    var service_app = $('#service_app').val();
	    var limit = $('#limit').val();
	    var version = $('#version').val();
	    getUser(access_token, service_app);
	    $.ajax({
            url: urlApi + 'notifications',
            data: 'access_token='+ access_token +'&service_app='+ service_app +'&limit='+ limit +'&version='+ version,
            dataType: 'json',
            type: "GET",
            beforeSend: function() {
                $('#result').html('Loading...');
            },
            success:function(result) {
                //console.log(result);
                var strHtml = '<table class="table table-hover">';
                var classType = 'btn-success';
                for(i=0;i<result.notifications.length;i++){
                    var fileId = 0;
                    var fileThumb = '';
                    var fileOrigin = '';
                    var actorAvatar = '';
                    var callOpenPhoto = ''
                    var txtOpen = 'Open';
                    if(result.notifications[i].resource == null) {
                    } else {
                        actorAvatar = result.notifications[i].actor.avatar;
                        fileId = result.notifications[i].resource.file.id;
                        fileThumb = result.notifications[i].resource.file.thumbnail;
                        fileOrigin = result.notifications[i].resource.file.url;
                        getUserOpenPhoto(result.notifications[i].resource.file.id, access_token,service_app)
                    }
                    if(result.notifications[i].type == 'open_file'){
                        classType = 'btn-info';
                    } else if(result.notifications[i].type == 'new_file'){
                        classType = 'btn-success';
                        console.log(result.notifications[i]);
                        if(result.notifications[i].is_read == 1) {
                            txtOpen = 'Opened';
                        }
                        callOpenPhoto = "<span class=\"btn btn-info\" onclick=\"openPhoto(this,'"+ access_token +"','"+ service_app +"',"+ result.notifications[i].resource.id +",'"+ version +"'); return false;\">"+ txtOpen +"</span>";
                    } else if(result.notifications[i].type == 'join_service'){
                         classType = 'btn-warning';
                    } else if(result.notifications[i].type == 'send'){
                        classType = 'btn-danger';
                    }
                    strHtml += 	'<tr>';
                    strHtml += 		'<td style="width:10%;text-align:center;">'+ result.notifications[i].id +'</td>';
                    strHtml += 		'<td style="width:10%;text-align:center;"><img src="'+ actorAvatar +'" class="margin" height="50"/></td>';
                    strHtml += 		'<td><h4>'+ result.notifications[i].content +'</h4><div class="opend_'+ fileId +'" style="background: #f5f5f5;margin-top:10px; padding-top:10px;">Loading...</div></td>';
                    strHtml += 		'<td style="width:5%;text-align:right;"><span class="btn '+ classType +'">'+ result.notifications[i].type +'</span></td>';
                    strHtml += 		'<td style="width:5%;text-align:right;"><span class="btn btn-default">'+ result.notifications[i].number_user_open_photo +'</span></td>';
                    strHtml += 		'<td style="width:10%;text-align:center;vertical-align: middle"><img src="'+ fileThumb +'" class="margin" height="30"/></td>';
                    strHtml += 		'<td style="width:10%;text-align:center;vertical-align: middle"><img src="'+ fileOrigin +'" class="margin" height="50"/></td>';
                    strHtml += 		'<td style="width:10%;text-align:center;vertical-align: middle"">'+ callOpenPhoto +'</td>';
                    strHtml += 		'<td style="width:10%;text-align:center;vertical-align: middle""><span class="btn btn-default">'+ result.notifications[i].created_at +'</span></td>';
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
			data: 'access_token='+ access_token +'&service_app='+ service_app +'&version=3.1',
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

	function openPhoto(obj,access_token, service_app, message_id, version) {
        $.ajax({
            url: urlApi + 'messages/'+ message_id +'/opened',
            data: 'access_token='+ access_token +'&service_app='+ service_app +'&version='+ version,
            dataType: 'json',
            type: "PUT",
            beforeSend: function() {
                $(obj).html('Openning...');
            },
            success:function(result) {
                $(obj).html('Opened');
            },
            error: function(jqXHR){
                $(obj).html('Opened');
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