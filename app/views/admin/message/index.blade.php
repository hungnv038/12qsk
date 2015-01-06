@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'message', 'method' => 'GET')) }}
                    <div class="col-lg-5">
                        {{Form::label('from_name', 'From name: ', array('style' => 'width:100px;text-align:right;'))}}
                        {{Form::text('from_name', isset($data['from_name']) ? $data['from_name'] : '')}} <br/>
                        {{Form::label('to_name', 'To name: ', array('style' => 'width:100px;text-align:right;'))}}
                        {{Form::text('to_name', isset($data['to_name']) ? $data['to_name'] : '')}}
                    </div>
                    <div class="col-lg-5">
                        {{Form::label('from_date', 'Date: ')}}
                        {{Form::text('from_date', isset($data['from_date']) ? $data['from_date'] : '')}}
                        {{Form::label('to_date', ' to ')}}
                        {{Form::text('to_date', isset($data['todate_date']) ? $data['to_date'] : '')}}
                    </div>
                    <div class="col-lg-2">
                        {{Form::submit('Search', array('class' => 'btn btn-xs btn-primary'));}}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    @foreach($messages as $message)
        <div class="col-lg-3 text-left">
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php if($message->gif != ''): ?>
                        <img src="{{$message->gif}}" width="100%"/>
                    <?php endif; ?>
                    <strong>ID:</strong> {{$message->id}} <br/>
                    <strong>From name:</strong> {{$message->from_name}}<br/>
                    <strong>To name:</strong> {{$message->to_name}}<br/>
                    <strong>File ID:</strong> {{$message->file_id}}<br/>
                    <strong>Created:</strong> {{$message->created_at}}
                </div>
            </div>
        </div>
    @endforeach
</div>
@stop