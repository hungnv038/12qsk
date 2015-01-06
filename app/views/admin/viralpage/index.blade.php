@extends('admin.layouts.default')
@section('content')
<script>
    var urlApi = '<?php echo URL::to('/') ?>/';
    function getMessageFromViralPage() {
	    var from_name = $('#from_name').val();
	    var from_date = $('#from_date').val();
	    var to_date = $('#to_date').val();
	    var version = $('#version').val();
	    $.ajax({
            url: urlApi + 'getMessageFromViralPage',
            data: 'version='+ version +'&from_name='+ from_name +'&from_date='+ from_date +'&to_date='+ to_date,
            dataType: 'json',
            type: "POST",
            beforeSend: function() {
                $('#result').html('Loading...');
            },
            success:function(result) {
                $('#result').html(result);
            },
            error: function(jqXHR){
                $('#result').html(jqXHR.responseText);
            }
        });
	}
	function openPhoto(obj, message_id, version) {
        $.ajax({
            url: urlApi + 'messages-viral/' + message_id,
            data: 'version='+ version,
            dataType: 'json',
            type: "PUT",
            beforeSend: function() {
                $(obj).html('Opending...');
            },
            success:function(result) {
                $(obj).html('Opended');
            },
            error: function(jqXHR){
                $(obj).html('Opended');
                getMessageFromViralPage();
            }
        });
    }
</script>
<div class="row">
    <div class="col-lg-10">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'getMessageFromViralPage', 'method' => 'GET')) }}
                    {{Form::label('from_name', 'From Name: ')}}
                    {{Form::text('from_name', '')}}
                    {{Form::label('from_date', 'From Date: ')}}
                    {{Form::text('from_date', '')}}
                    {{Form::label('to_date', 'To Date: ')}}
                    {{Form::text('to_date', '')}}
                    {{Form::label('version', 'Version: ')}}
                    {{Form::select('version', array('3.1' => '3.1', '3.01' => '3.01'), '3.1')}}
                    {{Form::submit('Get Message from Viral', array('class' => 'btn btn-xs btn-primary', 'onclick' => 'getMessageFromViralPage(); return false;'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="panel-body">
            <a class="btn btn-circle btn-primary" href="<?php echo URL::to('/postMessage') ?>" target="_blank">Post Message</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <div id="result"></div>
        </div>
    </div>
</div>
@stop