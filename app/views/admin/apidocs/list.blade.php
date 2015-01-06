<?php $groupApi = ''; ?>
@foreach($apis as $api)
    <?php
        $classMethod = 'badge-success';
        if($api->method == 'POST') {
            $classMethod = 'badge-warning';
        } elseif($api->method == 'PUT') {
            $classMethod = 'badge-info';
        }
    ?>
    @if($groupApi == '' || $groupApi != $api->group_api)
        <?php $groupApi = $api->group_api ?>
        <div class="list-group-item list-group-item-success"> <h4 class="list-group-item-heading"><i class="fa fa-plus-square"></i> {{ ucfirst($api->group_api)}}</h4></div>
    @endif
    <a href="#" class="list-group-item" data-name="{{$api->name}}" data-id="{{$api->id}}" onclick="getApiDoc(this,'{{$api->name}}','api'); return false;"><i class="fa fa-angle-double-right"></i> {{$api->name}} <span class="badge {{$classMethod}}">{{$api->method}}</span></a>
@endforeach

