@if($subStatus != Constants::STATUS_DRAW)
    <button class="btn btn-small btn-primary" style="float: right;" onclick="createPackage(this,'sub'); return false">Create SubPackage</button>
@else
    <button class="btn btn-small btn-primary" style="float: right;" onclick="editItem(this,0,0,'new'); return false">Add Item</button>
@endif