@extends('admin.layouts.default')
@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="panel panel-info">
            <div class="panel-body">
                {{ Form::open(array('url' => 'logs', 'method' => 'GET')) }}
                    {{Form::label('level', 'Level: ')}}
                    {{Form::select('level', array('' => 'Select Level', 'error' => 'Error', 'info' => 'Info'), isset($data['level']) ? $data['level'] : '')}}
                    {{Form::label('php_sapi_name', 'API Name: ')}}
                    {{Form::text('php_sapi_name', isset($data['php_sapi_name']) ? $data['php_sapi_name'] : '')}}
                    {{Form::label('php_sapi_name', 'Error Code: ')}}
                    {{Form::text('error_code', isset($data['error_code']) ? $data['error_code'] : '')}}
                    {{Form::label('message', 'Message: ')}}
                    {{Form::text('message', isset($data['message']) ? $data['message'] : '')}}
                    {{Form::submit('Filter', array('class' => 'btn btn-xs btn-primary'));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="">
            <div class="panel-body">
                {{ Form::open(array('url' => 'deletelogs', 'method' => 'POST')) }}
                    {{Form::submit('Delete All', array('class' => 'btn btn-sm btn-danger', 'style' => 'float:right;', 'onclick' => "return confirm('Are you sure you want to delete all?')"));}}
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th style="width: 10%;">API Name</th>
                    <th>Error Code</th>
                    <th>Level</th>
                    <th>Message</th>
                    <th>RunTime</th>
                    <th style="width: 8%;">Created</th>
                </tr>
                @foreach($logs as $log)
                    <?php
                        switch($log->level) {
                            case 'error':
                                $disClass = 'danger';
                                break;  
                            case 'warning':
                                $disClass = 'warning';
                                break;  
                            default:
                                $disClass = 'success';
                        }
                    ?>
                    <tr class="{{$disClass}}">
                        <td>{{$log->php_sapi_name}}</td>
                        <td>{{$log->error_code}}</td>
                        <td>{{$log->level}}</td>
                        <td style="width: 50%;"><p style="max-width: 700px; word-wrap: break-word;">{{$log->message}}</p></td>
                        <td>{{$log->end_time - $log->start_time}}</td>
                        <td style="text-align: center;">{{$log->created_at}}</td>
                    </tr>   
                @endforeach
            </table>
        </div>
        {{$logs->appends($data)->links()}}
    </div>
</div>
@stop