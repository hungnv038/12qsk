@if($alert['error'] == 1)
    <div class="alert alert-danger alert-dismissable">{{$alert['msg']}}</div>
@elseif($alert['error'] == 0)
    <div class="alert alert-success">{{$alert['msg']}}</div>
@endif
<div class="panel panel-default">
    <div class="panel-body">
        {{ Form::open(array('url' => '#', 'method' => 'POST', 'role' => 'form', 'enctype'=>'multipart/form-data')) }}
            <div class="form-group">
                {{Form::label('package_file', 'Package file (Zip): ', array('class' => 'control-label'))}}
                {{Form::file('package_file', array('class' => 'form-control'))}}
            </div>
            <div class="form-group">
                {{Form::label('import_type', 'Import Type', array('class' => 'control-label'))}}
                {{Form::select('import_type', array(
                        'default' => 'Default',
                        'sub' => 'Sub Package',
                      ));
                }}
            </div>
            <div class="form-group">
                {{Form::label('platform', 'Platform', array('class' => 'control-label'))}}
                {{Form::select('platform', InputHelper::$platform_availables)}}
            </div>
            <div class="form-group">
                {{Form::label('version', 'Version', array('class' => 'control-label'))}}
                {{Form::select('version', InputHelper::$ver_availables)}}
            </div>
            <div class="form-group" style="float: right">
                {{Form::submit('Import', array('onclick'=>'StickerPackage.saveImportItem(this);return false;', 'class' => 'btn btn-primary', 'data-loading-text' => 'Importing...'))}}
            </div>
        {{ Form::close() }}
    </div>
</div>
