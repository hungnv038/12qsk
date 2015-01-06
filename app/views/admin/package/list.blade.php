<div class="panel panel-default" id="package_items_header">
    <div class="panel-heading">
        <div class="row">
            <div class="col-lg-6">
                Plastform:
                {{Form::select('platform', $platformAvailables, $platform, array('id' => 'platform', 'onchange' => 'changeData();'))}}
                Versions:
                {{Form::select('version', $versionAvailables, $version, array('id' => 'version', 'onchange' => 'changeData();'))}}
                <button class="btn btn-small btn-primary" onclick="getPackageItems(this,{{$packageId}},'items')">Get Items</button>
            </div>
            <div class="col-lg-6" id="package_sub_controlbutton" style="display:{{$type == 'page' ? 'none;':'block'}}">
                @if($subStatus != Constants::STATUS_DRAW)
                    <button class="btn btn-small btn-primary" style="float: right;" onclick="createPackage(this,'sub'); return false"><span class="glyphicon glyphicon-plus"></span> Sub Package</button>
                @else
                    <div class="btn-group" style="float: right;">
                        <button class="btn badge btn-small btn-danger">Draw</button>
                        <button class="btn btn-small btn-primary" onclick="editItem(this,0,{{$subId}},'new'); return false">Add Item</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div id="package_items">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th style="width: 3%;text-align: center"><input type="checkbox" name="checkAll" id="checkAll" /></th>
            <th style="width:10%;text-align: center">Item ID</th>
            <th style="width: 15%;text-align: center">Thumbnail</th>
            <th style="width: 15%;text-align: center">Gif</th>
            <th style="">Package Name</th>
            <th style="width: 15%;text-align: center">Updated at</th>
            <th style="width: 15%;text-align: center">Action</th>
            <th style="width: 5%;text-align: center">Status</th>
        </tr>
        @foreach($stickers as $sticker)
            <?php
                if($sticker->final == 1) {
                    $stdClass = 'warning';
                } else {
                    $stdClass = 'success';
                }
                if($sticker->status == Constants::STATUS_DRAW) {
                    $status = '<span class="badge badge-error">'. $sticker->status .'</span>';
                } elseif($sticker->status == Constants::STATUS_NEW) {
                    $status = '<span class="badge badge-success">'. $sticker->status .'</span>';
                } elseif($sticker->status == Constants::STATUS_DELETE) {
                    $stdClass = 'danger';
                    $status = '<span class="badge badge-warning">'. $sticker->status .'</span>';
                } elseif($sticker->status == Constants::STATUS_UPDATE) {
                    $status = '<span class="badge badge-info">'. $sticker->status .'</span>';
                } else {
                    $status = '';
                }
            ?>
            <tr class="{{$stdClass}}">
                <td style="width: 3%;text-align: center; vertical-align: middle"><input type="checkbox" name="checkAll" class="checkAll" /></td>
                <td style="text-align: center">{{$sticker->item_id}}</td>
                <td style="width: 15%;text-align: center"><img src="{{$sticker->thumbnail_file}}" width="50"/></td>
                <td style="width: 15%;text-align: center"><img src="{{$sticker->item_file}}" width="50"/></td>
                <td>{{$sticker->package_name}}</td>
                <td style="width: 15%;text-align: center">{{$sticker->updated_at}}</td>
                <td style="width: 15%;text-align: center;vertical-align: middle">
                    @if($subStatus == Constants::STATUS_DRAW)
                        <a class="btn btn-small btn-info" href="#" onclick="editItem(this,'{{$sticker->item_id}}',{{$subId}},'edit'); return false;">Edit</a>
                    @endif
                </td>
                <td style="width: 5%;text-align: center; vertical-align: middle">{{$status}}</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="form-group" id="package_sub_bottom_controlbutton">
    @if($subId != 0)
        {{Form::submit('Save Final', array('onclick'=>"saveFinalItems(this,'{$packageId}','{$subId}');return false;", 'class' => 'btn btn-primary', 'data-loading-text' => "Saving..."))}}
    @else
        <a href="#" onclick="uploadZipToS3(this,'{{$packageId}}');return false;" class="btn btn-success">Upload To S3</a>
        <a href="#" onclick="downloadZipPackage(this,'{{$packageId}}');return false;" class="btn btn-warning">Download Zip</a>
    @endif
</div>
