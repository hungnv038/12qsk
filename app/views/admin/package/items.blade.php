<script>
    $(function(){
        $('#checkAll').click(function(e){
            var table= $(e.target).closest('table');
            $('td input:checkbox',table).prop('checked',this.checked);
        });
        $('.checkAll').click(function(e){
            if(this.checked == false) {
                $('#checkAll').prop('checked',this.checked);
            }
        });
        $("#sortTable").rowSorter({
            handler: "td.sorter",
            onDrop: function(tbody, row, index, oldIndex) {
                $(tbody).parent().find("tfoot > tr > td").html((oldIndex + 1) + ". row moved to " + (index + 1));

                var item_ids = $('.checkAll').map(function() {
                    return $(this).val();
                }).get();
                $(tbody).parent().find("tfoot > tr > td").html(item_ids);
            }
        });
    });
</script>
@if($alert['error'] == 1)
    <div class="alert alert-danger">{{$alert['msg']}}</div>
@elseif($alert['error'] == 0)
    <div class="alert alert-success">{{$alert['msg']}}</div>
@endif
<table class="table table-bordered table-hover table-striped" id="sortTable">
    <thead>
    <tr>
        @if($subStatus != Constants::STATUS_DRAW)
            <th style="width: 3%;text-align: center"><a href="#" data-loading-text="..." onclick="StickerPackage.saveSortedItems(this);return false;" class="btn btn-small btn-warning"><i class="fa fa-save"></i></a></th>
            <th style="width: 3%;text-align: center"><input type="checkbox" name="checkAll" id="checkAll" /></th>
        @endif
        <th style="width:10%;text-align: center">Item ID</th>
        <th style="width: 15%;text-align: center">Thumbnail</th>
        <th style="width: 15%;text-align: center">Gif</th>
        <th style="">Text Quote</th>
        <th style="width: 15%;text-align: center">Sorted</th>
        <th style="width: 15%;text-align: center">Action</th>
        <th style="width: 5%;text-align: center">Status</th>
    </tr>
    </thead>
    <tbody>
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
            @if($subStatus != Constants::STATUS_DRAW)
                <td class="sorter" style="width: 3%;text-align: center; vertical-align: middle"><i class="fa fa-arrows"></i></td>
                <td style="width: 3%;text-align: center; vertical-align: middle"><input type="checkbox" value="{{$sticker->id}}" name="checkAll" class="checkAll" /></td>
            @endif
            <td style="text-align: center">{{$sticker->item_id}}</td>
            <td style="width: 15%;text-align: center; vertical-align: middle"><img src="{{$sticker->thumbnail_file != '' ? $sticker->thumbnail_file : '/img/nophoto.jpg'}}" height="35"/></td>
            <td style="width: 15%;text-align: center; vertical-align: middle"><img src="{{$sticker->item_file != '' ? $sticker->item_file : '/img/nophoto.jpg'}}" height="35"/></td>
            <td>{{$sticker->text_quote}}</td>
            <td style="width: 15%;text-align: center">{{$sticker->sorted}}</td>
            <td style="width: 15%;text-align: center; vertical-align: middle">
                @if($subStatus == Constants::STATUS_DRAW)
                    <a class="btn btn-small btn-info" href="#" data-loading-text="Creating..." onclick="StickerPackage.editItem(this,'{{$sticker->item_id}}','edit'); return false;">Edit</a>
                @else
                    <a class="btn btn-small btn-warning" href="#" data-loading-text="..." onclick="StickerPackage.moveTop(this,'{{$sticker->item_id}}','edit'); return false;"><i class="fa fa-sort-up"></i></a>
                @endif
            </td>
            <td style="width: 5%;text-align: center; vertical-align: middle">{{$status}}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr><td colspan="9"></td></tr>
    </tfoot>
</table>