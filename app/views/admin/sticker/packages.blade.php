<div class="list-group">
    <div class="list-group-item list-group-item-success list-group-item-heading">
        <h4 class="list-group-item-heading">Packages
        <button data-toggle="modal" data-target="#createPackageModel" type="button" class="btn btn-small btn-primary navbar-right"><span class="glyphicon glyphicon-plus"></span> New</button></h4>
    </div>
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
        <a href="#" class="list-group-item" data-pid="{{$package->id}}" onclick="getPackageItems(this,{{$package->id}},'page'); return false;"><span class="badge">{{$package->counter}}</span>{{$package->package_name}}<span class="badge badge-info">{{$package->language}}</span>{{$strNew}}</a>
    @endforeach
</div>