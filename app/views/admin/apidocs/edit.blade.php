@extends('admin.layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="list-group">
                <div class="list-group-item active list-group-item-heading">
                    <h4 class="list-group-item-heading">
                        APIs
                        <a href="{{URL::to('/setApiDoc') .'?id=0'}}" class="btn btn-small btn-info navbar-right">Add</a>
                    </h4>
                </div>
                <?php $groupApi = ''; ?>
                @foreach($apis as $api)
                    @if($groupApi == '' || $groupApi != $api->group_api)
                        <?php $groupApi = $api->group_api ?>
                        <div class="list-group-item list-group-item-success"> <h4 class="list-group-item-heading"><i class="fa fa-plus-square"></i> {{ucfirst($api->group_api)}}</h4></div>
                    @endif
                    <a href="{{URL::to('/setApiDoc') .'?id='. $api->id}}" class="list-group-item {{isset($model->id) && $model->id == $api->id ? 'active' : ''}}" ><i class="fa fa-angle-double-right"></i> {{$api->name}}<span class="badge badge-info">{{$api->version}}</span><span class="badge badge-info">{{$api->service}}</span></a>
                @endforeach
            </div>
        </div>
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">{{isset($model->id) ? 'Edit' : 'Add'}}
                        API Guild
                        @if(isset($model->id))
                            <div class="btn-group" style="float: right">
                                <a class="btn btn-small btn-danger" href="{{URL::to('/setApiDoc') .'?action=delete&id='. $model->id}}" onclick="return confirm('Are you sure you want to delete this API?');">Delete</a>
                                <button class="btn btn-small btn-warning dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
                                    Clone <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="min-width: 130px">
                                    @foreach($availablesVersions as $aVersion)
                                        @if($model->version != $aVersion)
                                            <li role="presentation"><a role="menuitem" tabindex="-1" href="{{URL::to('/setApiDoc') .'?action=clone&new_version='. $aVersion .'&id='. $model->id}}">{{$aVersion}}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </h3>
                </div>
                <div class="panel-body">
                    @if($alert['error'] == 1)
                        <div class="alert alert-danger">{{$alert['msg']}}</div>
                    @elseif($alert['error'] == 0)
                        <div class="alert alert-success">{{$alert['msg']}}</div>
                    @endif
                    {{ Form::open(array('url' => 'setApiDoc', 'method' => 'POST', 'role' => 'form')) }}
                        <div class="form-group">
                            {{Form::label("group_api", 'Group: ', array('class' => 'control-label'))}}
                            {{Form::select("group_api", array('chanels' => 'chanels','devices' => 'devices', 'movies' =>'movies'), isset($model->group_api)?$model->group_api:'')}}
                        </div>
                        <div class="form-group">
                            {{Form::label('name', 'Name: ', array('class' => 'control-label'))}}
                            {{Form::text('name', isset($model->name) ? $model->name : '', array('class' => 'form-control'))}}
                        </div>
                        <div class="form-group">
                            {{Form::label("method", 'Method: ', array('class' => 'control-label'))}}
                            {{Form::select("method", array('GET' => 'GET','POST' => 'POST','PUT' => 'PUT'), isset($model->method) ? $model->method : '')}}
                        </div>
                        <div class="form-group">
                            {{Form::label("content[summary]", 'Summary: ', array('class' => 'control-label'))}}
                            {{Form::textarea("content[summary]", isset($model->content['summary']) ? $model->content['summary'] : '', array('class' => 'form-control', 'style' => 'height: 60px;'))}}
                        </div>
                        <div class="form-group">
                            Fields
                        </div>
                        @for($i = 1; $i < 15; $i++)
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-2">
                                    {{Form::label("field", 'Name: ', array('class' => 'control-label'))}}
                                    {{Form::text("content[fields][$i][name]", isset($model->content['fields'][$i]['name']) ? $model->content['fields'][$i]['name'] : '', array('class' => 'form-control'))}}
                                </div>
                                <div class="col-lg-2">
                                    {{Form::label("field", 'Type: ', array('class' => 'control-label'))}}
                                    {{Form::select("content[fields][$i][type]", array('Boolean' => 'Boolean','String' => 'String','Entities' => 'Entities','Int' => 'Int','Type' => 'Type','Int64' => 'Int64','Tweets' => 'Tweets'), isset($model->content['fields'][$i]['type']) ? $model->content['fields'][$i]['type'] : 'String', array('class' => 'form-control'))}}
                                </div>
                                <div class="col-lg-2">
                                    {{Form::label("field", 'Require: ', array('class' => 'control-label'))}}
                                    {{Form::select("content[fields][$i][require]",array('optional' => 'optional','semi-optional'=>'semi-optional','require'=>'require'), isset($model->content['fields'][$i]['require']) ? $model->content['fields'][$i]['require'] : 'optional', array('class' => 'form-control'))}}
                                </div>
                                <div class="col-lg-6">
                                    {{Form::label("field", 'Description: ', array('class' => 'control-label'))}}
                                    {{Form::textarea("content[fields][$i][description]", isset($model->content['fields'][$i]['description']) ? $model->content['fields'][$i]['description'] : '', array('class' => 'form-control', 'style' => 'height: 60px;'))}}
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div class="form-group">
                            {{Form::label("content[output]", 'Outputs: ', array('class' => 'control-label'))}}
                            {{Form::textarea("content[output]", isset($model->content['output']) ? $model->content['output'] : '', array('class' => 'form-control'))}}
                        </div>
                        <div class="form-group">
                            {{Form::hidden("id", isset($model->id) ? $model->id : 0, array('class' => 'form-control'))}}
                            {{Form::submit('Save', array('class' => 'btn btn-primary'))}}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop