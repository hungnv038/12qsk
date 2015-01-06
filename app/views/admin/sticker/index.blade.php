@extends('admin.layouts.default')
@section('content')
    <link href="<?php echo URL::to('/') ?>/admintheme/js/plugins/bootstrapvalidator/css/bootstrapValidator.min.css" rel="stylesheet"/>
    <script src="<?php echo URL::to('/') ?>/admintheme/js/plugins/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
    <script>
        var urlApi = '<?php echo URL::to('/') ?>/';

        function getPackages() {
            $.ajax({
                url: urlApi + 'getPackages',
                data: 'type=particle',
                dataType: 'html',
                type: "GET",
                beforeSend: function() {
                    $('#groupPackage').html('Loading...');
                },
                success:function(result) {
                    $('#groupPackage').html(result);
                },
                error: function(jqXHR){
                    $('#groupPackage').html(jqXHR.responseText);
                }
            });
        }

        function getPackageItems(obj, package_id, type) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            $.ajax({
                url: urlApi + 'getPackageItems',
                data: 'package_id='+ package_id +'&type='+ type +'&platform='+ platform +'&version='+ version,
                dataType: 'html',
                type: "GET",
                beforeSend: function() {
                    if(type == 'page') {
                        $('.list-group-item').removeClass('active');
                        $(obj).addClass('active');
                        $('#result').html('Loading...');
                    } else {
                        $('#package_items').html('Loading...');
                    }
                },
                success:function(result) {
                    if(type == 'page') {
                        $(obj).addClass('active');
                    }
                    $('#result').html(result);

                },
                error: function(jqXHR){
                    $('#result').html(jqXHR.responseText);
                }
            });
        }

        function createPackage(obj, type) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            var package_id = $('.list-group-item.active').data('pid');
            var name = '', language = '';
            if(type == 'main') {
                name = $('#createPackageFrom').find('#name').val();
                language = $('#createPackageFrom').find('#language').val();
                if(name == '') return false;
            }
            $.ajax({
                url: urlApi + 'createPackage',
                data: 'type='+ type +'&platform='+ platform +'&version='+ version +'&package_id='+ package_id +'&name='+ name +'&language='+ language,
                dataType: 'html',
                type: "POST",
                beforeSend: function() {
                    //$(obj).html('Creating...');
                    $(obj).button('loading')
                },
                success:function(result) {
                    if(type == 'sub') {
                        $(obj).remove();
                        $('.list-group-item.active').find('.badge-error').remove();
                        $('.list-group-item.active').append('<span class="badge badge-error">Draw</span>');
                        $('#package_items').html(result);
                    } else if(type == 'main') {
                        $(obj).button('reset')
                        getPackages();
                        $('#createPackageModel').modal('hide')
                    }
                },
                error: function(jqXHR){
                    //$('#result').html(jqXHR.responseText);
                }
            });
        }

        function editItem(obj, item_id, sub_id, type) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            var package_id = $('.list-group-item.active').data('pid');
            $.ajax({
                url: urlApi + 'editPackageItem',
                data: 'item_id='+ item_id +'&type='+ type +'&platform='+ platform +'&version='+ version +'&package_id='+ package_id +'&sub_id='+ sub_id,
                dataType: 'html',
                type: "POST",
                beforeSend: function() {
                    $('#package_sub_bottom_controlbutton').hide();
                    $('#package_items').html('Edit Item...');
                    $('#package_items_header select').attr('disabled','disabled');
                    $('#package_items_header button').attr('disabled','disabled');
                },
                success:function(result) {
                    $('#package_items').html(result);
                },
                error: function(jqXHR){
                    $('#package_items_header select').removeAttr('disabled');
                    $('#package_items_header button').removeAttr('disabled');
                    $('#package_items').html(jqXHR.responseText);
                }
            });
        }
        function saveItem(obj) {
            var form = $('form')[0]; // You need to use standart javascript object here
            var formData = new FormData(form);
            $.ajax({
                url: urlApi + 'editPackageItem?type=save',
                data: formData,
                dataType: 'html',
                type: "POST",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(obj).val('Saving...');
                },
                success:function(result) {
                    $('#package_items').html(result);
                    $('#package_items_header select').removeAttr('disabled');
                    $('#package_items_header button').removeAttr('disabled');
                    $('#package_sub_bottom_controlbutton').show();
                },
                error: function(jqXHR){
                    $('#package_items_header select').removeAttr('disabled');
                    $('#package_items_header button').removeAttr('disabled');
                }
            });
        }

        function saveFinalItems(obj, package_id, sub_id) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            //var package_id = $('.list-group-item.active').slice(1).data('pid');
            $.ajax({
                url: urlApi + 'savePackageItemFinal',
                data: 'platform='+ platform +'&version='+ version +'&package_id='+ package_id +'&sub_id='+ sub_id,
                dataType: 'html',
                type: "GET",
                beforeSend: function() {
                    $(obj).button('loading')
                },
                success:function(result) {
                     $(obj).button('reset')
                     getPackages();
                },
                error: function(jqXHR){

                }
            });
        }

        function downloadZipPackage(obj, package_id) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            window.location.href = urlApi + 'downloadZipPackage?platform='+ platform +'&version='+ version +'&package_id='+ package_id;
        }
        function uploadZipToS3(obj, package_id) {
            var platform = $('#platform').val();
            var version = $('#version').val();
            window.location.href = urlApi + 'uploadZipToS3?platform='+ platform +'&version='+ version +'&package_id='+ package_id;
        }


        function changeData() {
            $('#package_sub_controlbutton').hide();
        }

        function showMyImage(fileInput, show_id) {
            var files = fileInput.files;
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    continue;
                }
                var img=document.getElementById(show_id);
                img.file = file;
                var reader = new FileReader();
                reader.onload = (function(aImg) {
                    return function(e) {
                        aImg.src = e.target.result;
                    };
                })(img);
                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            $('#createPackageFrom').bootstrapValidator({
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        validators: {
                            notEmpty: {
                                message: 'The name is required'
                            }
                        }
                    }
                }
            });
            $('#createPackageModel').on('shown.bs.modal', function() {
                $('#createPackageFrom').bootstrapValidator('resetForm', true);
            });
        });
    </script>
    <div class="row">
        <div class="col-lg-4" id="groupPackage">
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
        </div>
        <div class="col-lg-8">
            <div id="result"></div>
        </div>
    </div>
    <div class="modal fade" id="createPackageModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Create Package</h4>
                </div>
                <div class="modal-body">
                    <form id="createPackageFrom" method="post" class="">
                        <div class="form-group">
                            {{Form::label('name', 'Package Name(*): ', array('class' => 'control-label'))}}
                            {{Form::text('name', '', array('class' => 'form-control'))}}
                        </div>
                        <div class="form-group">
                            {{Form::label('language', 'Language: ', array('class' => 'control-label'))}}
                            {{Form::select('language', InputHelper::$language_availables, 'en', array('class' => 'form-control'))}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="Creating..." onclick="createPackage(this,'main'); return false">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop
