@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'checkDatabase', 'method' => 'GET')) }}
                    {{Form::label('type', 'Type: ')}}
                    {{Form::select('type', array('topic' => 'Compare Topic', 'database' => 'Compare Database'), 'topic');}}
                    {{Form::submit('Check', array('class' => 'btn btn-xs btn-primary', 'onclick' => 'checkData(); return false;'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<script>
	var urlApi = '<?php echo URL::to('/') ?>/';
	function checkData() {
	    var type = $('#type').val();
	    $.ajax({
            url: urlApi + 'checkDatabase',
            data: 'type='+ type,
            dataType: 'json',
            type: "GET",
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