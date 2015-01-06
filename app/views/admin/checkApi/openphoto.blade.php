@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-info">
            {{ Form::open(array('id' =>'formUpload', 'url' => 'postMessage', 'method' => 'POST', 'enctype'=>'multipart/form-data'))}}
            <div class="panel-body">
                    {{Form::label('access_token', 'access_token: ')}}
                    {{Form::text('access_token', '6812c6a17e76b8b954b8bfc7e912f020')}}
                    {{Form::label('service_app', 'service_app: ')}}
                    {{Form::text('service_app', 'picchat')}}
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-4">{{Form::label('version', 'Version: ')}}</div>
                    <div class="col-lg-6">{{Form::select('version', array('3.01' => '3.01','3.1' => '3.1'), '3.1')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('photo', 'Photo: ')}}</div>
                    <div class="col-lg-6">{{Form::file('photo')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('thumbnail', 'Photo Thumbnail: ')}}</div>
                    <div class="col-lg-6">{{Form::file('thumbnail')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('gif', 'Gif: ')}}</div>
                    <div class="col-lg-6">{{Form::file('gif')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('to_facebook_ids', 'to_facebook_ids: ')}}</div>
                    <div class="col-lg-6">{{Form::text('to_facebook_ids', '1502903929958134,752371564785368')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('fb_friend_names', 'fb_friend_names: ')}}</div>
                    <div class="col-lg-6">{{Form::text('fb_friend_names', '')}}</div>
                </div>
                <div class="row">
                    <div class="col-lg-4">{{Form::label('fb_friend_avatars', 'fb_friend_avatars: ')}}</div>
                    <div class="col-lg-6">{{Form::text('fb_friend_avatars', '')}}</div>
                </div>
            </div>
            <div class="panel-body">
                {{Form::submit('Post Message', array('id'=>'submit','class' => 'btn btn-xs btn-primary', 'onclick' => 'sendPhoto(); return false;'));}}
            </div>
            {{ Form::close() }}
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
	function sendPhoto() {
	    var access_token = $('#access_token').val();
	    var service_app = $('#service_app').val();
	    var version = $('#version').val();
	    getUser(access_token, service_app, version);
	    createSession(access_token, service_app, version);
	}
	function createSession(access_token, service_app) {
	    console.log('createSession');
        $.ajax({
            url: urlApi + 'sessions',
            data: 'access_token='+ access_token +'&service_app='+ service_app +'&version='+ version,
            dataType: 'json',
            type: "POST",
            beforeSend: function() {
                $('#tag_session').html('Create session...');
            },
            success:function(result) {
                console.log(result);
                $('#tag_session').html('Create session... (Done)');
                if(result.id) {
                    postPhoto(access_token, service_app, result.id, version);
                    postMessage(access_token, service_app, result.id, version);
                }
            },
            error: function(jqXHR){
                $('#tag_session').html(jqXHR.responseText);
            }
        });
    }
    function postPhoto(access_token, service_app, session_id, version) {
        console.log('postPhoto');
        var form = $('form')[0]; // You need to use standart javascript object here
        var formData = new FormData(form);
        formData.append('session_id', parseInt(session_id));
        $.ajax({
            url: urlApi + 'files?access_token='+ access_token +'&service_app='+ service_app +'&version='+ version,
            data: formData,
            dataType: 'json',
            type: "POST",
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#tag_photo').html(' > Upload photo...');
            },
            success:function(result) {
                console.log(result);
                $('#tag_photo').html(' > Upload photo... (Done)');
            },
            error: function(jqXHR){
                $('#tag_photo').html(jqXHR.responseText);
            }
        });
    }
    function postMessage(access_token, service_app, session_id, version) {
        console.log('postMessage');
        var formData = new FormData();
            formData.append('session_id', parseInt(session_id));
            formData.append('to_facebook_ids', $('#to_facebook_ids').val());
            formData.append('fb_friend_names', $('#fb_friend_names').val());
            formData.append('fb_friend_avatars', $('#fb_friend_avatars').val());
        $.ajax({
            url: urlApi + 'messages?access_token='+ access_token +'&service_app='+ service_app +'&version='+ version,
            data: formData,
            dataType: 'json',
            type: "POST",
            contentType: false,
            processData: false,
            beforeSend: function() {
                $('#tag_message').html(' > Post Message...');
            },
            success:function(result) {
                console.log(result);
                $('#tag_message').html(' > Post Message... (Done) > Finish');
            },
            error: function(jqXHR){
                $('#tag_message').html(jqXHR.responseText);
            }
        });
    }
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
				<div id="result" class="box-body table-responsive no-padding">
				    <div id="tag_session" class="col-lg-3"></div>
				    <div id="tag_photo" class="col-lg-3"></div>
				    <div id="tag_message" class="col-lg-3"></div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop