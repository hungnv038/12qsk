@extends('admin.layouts.default')
@section('content')
    <script>
        var urlApi = '<?php echo URL::to('/') ?>/';
        function getApiDoc(obj, name, type) {
            var service = $('#service').val();
            var version = $('#version').val();
            if(version === undefined) {
                version = '1.0';
            }
            if(type == 'detail') {
                //id = $('.list-group-item.active').slice(1).data('id');
                name = $('.list-group-item.active').slice(1).data('name');
            }
            $.ajax({
                url: urlApi + 'getApiDoc',
                data: 'name='+ name +'&service='+ service +'&version='+ version,
                dataType: 'html',
                type: "GET",
                beforeSend: function() {
                    if(type == 'api') $('.list-group-item').slice(1).removeClass('active');
                    $(obj).addClass('active');
                    $('#result').html('Loading...');
                },
                success:function(result) {
                    if(type == 'api') $(obj).addClass('active');
                    $('#result').html(result);
                },
                error: function(jqXHR){
                    $('#result').html(jqXHR.responseText);
                }
            });
        }

        function getApiDocs(obj) {
            var service = $(obj).val();
            $.ajax({
                url: urlApi + 'apidocs',
                data: 'service='+ service +'&ajax=1',
                dataType: 'html',
                type: "GET",
                beforeSend: function() {
                    $('#list_api_group').html('Loading...');
                },
                success:function(result) {
                    $('#list_api_group').html(result);
                },
                error: function(jqXHR){
                    $('#list_api_group').html(jqXHR.responseText);
                }
            });
        }
    </script>
    <div class="row">
        <div class="col-lg-4">
            <div class="list-group">
                <div class="list-group-item active list-group-item-heading">
                    <h4 class="list-group-item-heading">APIs
                        <div style="float: right; visibility: hidden">
                            Service:
                            <select class="small" id="service" onchange="getApiDocs(this);">
                                <option value="picchat">picchat</option>
                                <option value="recor">recor</option>
                            </select>
                        </div>
                    </h4>
                </div>
                <div id="list_api_group">
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
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div id="result"></div>
        </div>
    </div>
@stop
