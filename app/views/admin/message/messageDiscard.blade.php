@extends('admin.layouts.default')
@section('content')
<div class='row'>
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Message Discard</h3>
            </div>
        </div>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <th >ID FILE</th>
                <th>IMAGE</th>
                <th>USER UPLOAD</th>
                <th>NUMBER FRIEND</th>
            </tr>
            @foreach($datas as $data)
                <tr >
                    <td>{{$data->file_id}}</td>
                    <td style="vertical-align: middle; text-align: center"><img src="<?php echo Setting::get('s3_url').'/message_photo/'. $data->file_name; ?>" height="150"/></td>
                    <td>{{$data->fb_id}}</td>
                    <td>{{isset($data->friend_selected) ? $data->friend_selected : 0}}</td>
                </tr>
            @endforeach
        </table>
        <div style="text-align: center">{{$datas->links()}}</div>
    </div>
</div>
@stop