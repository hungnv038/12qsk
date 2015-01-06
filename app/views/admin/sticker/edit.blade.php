<div class="panel panel-info">
    <div class="panel-body">
        {{ Form::open(array('url' => '#', 'method' => 'POST', 'role' => 'form', 'enctype'=>'multipart/form-data')) }}
            @if(isset($model->item_id) && $model->item_id != '')
                <div class="form-group">
                    {{Form::label('item_id', 'Item ID: ', array('class' => 'control-label'))}}
                    <span class="badge badge-info"> {{$model->item_id}} </span>
                    {{Form::hidden('item_id', $model->item_id)}}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-3">
                    <img src="{{isset($model->thumbnail_file) ? $model->thumbnail_file : ''}}" id="show_thumbnail_file" width="50"/>
                </div>
                <div class="col-lg-7">
                    {{Form::label('thumbnail_file', 'Thumbnail: ')}}
                    {{Form::file('thumbnail_file', array('onchange' => "showMyImage(this, 'show_thumbnail_file');"))}}
                    {{Form::hidden('thumbnail_file_old', isset($model->thumbnail_file_old) ? $model->thumbnail_file_old : '')}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <img src="{{isset($model->item_file) ? $model->item_file : ''}}" id="show_item_file"  width="50"/>
                </div>
                <div class="col-lg-7">
                    {{Form::label('item_file', 'Gif: ')}}
                    {{Form::file('item_file', array('onchange' => "showMyImage(this, 'show_item_file');"))}}
                    {{Form::hidden('item_file_old', isset($model->item_file_old) ? $model->item_file_old : '')}}
                </div>
            </div>
            <br>
            <div class="form-group">
                {{Form::label('text_quote', 'Text Quote: ', array('class' => 'control-label'))}}
                {{Form::text('text_quote', isset($model->text_quote) ? $model->text_quote : '', array('class' => 'form-control'))}}
            </div>
            <div class="form-group">
                {{Form::label('status', 'Status: ', array('class' => 'control-label'))}}
                {{Form::select('status', array('delete' => 'delete', 'new' => 'new', 'update' => 'update'), $statusDefault, array('class' => 'form-control1'))}}
            </div>
            {{Form::hidden('package_id', isset($model->package_id) ? $model->package_id : '', array('class' => 'form-control'))}}
            {{Form::hidden('version', isset($model->version) ? $model->version : '', array('class' => 'form-control'))}}
            {{Form::hidden('platform', isset($model->platform) ? $model->platform : '', array('class' => 'form-control'))}}
            {{Form::hidden('sub_id', $subId, array('class' => 'form-control'))}}
            {{Form::hidden('id', isset($model->id) ? $model->id : 0, array('class' => 'form-control'))}}
            <div class="form-group" style="float: right">
                {{Form::submit('Save', array('onclick'=>'saveItem(this);return false;', 'class' => 'btn btn-primary'))}}
                {{Form::button('Cancel', array('onclick'=>"getPackageItems(this,". $model->package_id .",'items'); return false;", 'class' => 'btn btn-warning'))}}
            </div>
        {{ Form::close() }}
    </div>
</div>
