<div class="list-group">
    @foreach($packages as $package)
        <?php
            if($package->sub_id > 0) {
                $strNew = '<span class="badge badge-error">Draw</span>';
                $subId = $package->sub_id;
            } else {
                $strNew = '';
                $subId = 0;
            }
        ?>
        <a href="#" class="list-group-item {{$package->id == $packageId ? 'active' : ''}}" data-pid="{{$package->id}}" onclick="StickerPackage.getPackageItems(this,{{$package->id}},'page'); return false;"><span class="badge">{{$package->counter}}</span>{{$package->package_name}}<span class="badge badge-info">{{$package->language}}</span>{{$strNew}}</a>
    @endforeach
</div>