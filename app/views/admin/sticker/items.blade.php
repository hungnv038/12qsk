@if($alert['error'] == 1)
    <div class="alert alert-danger">{{$alert['msg']}}</div>
@elseif($alert['error'] == 0)
    <div class="alert alert-success">{{$alert['msg']}}</div>
@endif
<table class="table table-bordered table-hover table-striped">
    <tr>
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
            <td style="text-align: center">{{$sticker->item_id}}</td>
            <td style="width: 15%;text-align: center"><img src="{{$sticker->thumbnail_file}}" width="50"/></td>
            <td style="width: 15%;text-align: center"><img src="{{$sticker->item_file}}" width="50"/></td>
            <td>{{$sticker->package_name}}</td>
            <td style="width: 15%;text-align: center">{{$sticker->updated_at}}</td>
            <td style="width: 15%;text-align: center; vertical-align: middle">
                @if($subStatus == Constants::STATUS_DRAW)
                    <a class="btn btn-small btn-info" href="#" onclick="editItem(this,'{{$sticker->item_id}}',{{$subId}},'edit'); return false;">Edit</a>
                @endif
            </td>
            <td style="width: 5%;text-align: center; vertical-align: middle">{{$status}}</td>
        </tr>
    @endforeach
</table>