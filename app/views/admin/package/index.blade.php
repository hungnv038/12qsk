@extends('admin.layouts.default')
@section('header')
    {{HTML::style('admintheme/js/plugins/bootstrapvalidator/css/bootstrapValidator.min.css')}}
    {{HTML::script('admintheme/js/plugins/package/package.js')}}
    {{HTML::script('admintheme/js/plugins/bootstrapvalidator/js/bootstrapValidator.min.js')}}
    {{HTML::script('admintheme/js/jquery.rowsorter.js')}}
@stop

@section('content')
    <div class="row" id="container_layout">
        <div class="col-lg-3">
            <div class="panel panel-default" id="package_items_header">
                <div class="panel-heading" data-loading-text="Loading..." id="package_loading">
                    Packages
                    <div class="btn-group navbar-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-small btn-warning" onclick="StickerPackage.importPackage(this);return false;"><span class="fa fa-stack-overflow"></span> Import</button>
                        <button data-toggle="modal" data-target="#createPackageModel" type="button" class="btn btn-small btn-primary"><span class="fa fa-plus"></span> New</button>
                    </div>
                </div>
            </div>
            <div id="group_package">

            </div>
        </div>

        <div class="col-lg-9" id="package_group_sub">
            <div class="panel panel-default" id="package_items_header">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-4">
                            Plastform:
                            {{Form::select('platform', $platformAvailables, '', array('id' => 'platform', 'onchange' => "StickerPackage.getPackageItems('noactive');"))}}
                            Versions:
                            {{Form::select('version', $versionAvailables, '', array('id' => 'version', 'onchange' => "StickerPackage.getPackageItems('noactive');"))}}
                        </div>
                        <div class="col-lg-8" id="package_sub_controlbutton">
                            <div class="btn-group" id="allow_save" role="group" aria-label="..." style="display: none;">
                                <a href="#" onclick="StickerPackage.saveFinalItems(this);return false;" class="btn btn-small btn-primary" data-loading-text="Saving..."><i class="fa fa-save"></i> Save Final</a>
                            </div>
                            <div class="btn-group" id="allow_upload" role="group" aria-label="..." style="display: none;">
                                <a href="#" data-loading-text="Uploading..." onclick="StickerPackage.uploadZipToS3(this);return false;" class="btn btn-small btn-success"><i class="fa fa-upload"></i> Upload To S3</a>
                                <a href="#" data-loading-text="Downloading..." onclick="StickerPackage.downloadZipPackage(this);return false;" class="btn btn-small btn-warning"><i class="fa fa-download"></i> Zip</a>
                                <a href="#" data-loading-text="Downloading..." onclick="StickerPackage.downloadFullZipPackage(this);return false;" class="btn btn-small btn-warning"><i class="fa fa-download"></i> Full Zip</a>
                                <button class="btn btn-small btn-danger" onclick="StickerPackage.cloneItem(this); return false"><i class="fa fa-comments-o"></i> Clone</button>
                            </div>
                            <div class="btn-group" id="allow_add" role="group" aria-label="..." style="float: right; display: none;">
                                <button type="button" class="btn btn-small btn-primary" onclick="StickerPackage.editItem(this,0,'new'); return false"><span class="fa fa-plus"></span> Add Item</button>
                                <button type="button" class="badge btn btn-small btn-danger">Draw</button>
                            </div>
                            <div class="btn-group" id="allow_create" role="group" aria-label="..." style="float: right; display: none;">
                                <button type="button" data-loading-text="Creating..." class="btn btn-small btn-primary" onclick="StickerPackage.createPackage(this,'sub'); return false"><span class="fa fa-plus"></span> Sub Package</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="msg_alert"></div>
            <div id="group_package_items">

            </div>
        </div>
    </div>


    <div class="modal fade" id="createPackageModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Create Package</h4>
                </div>
                <form action="" id="createPackageFrom" class="">
                    <div class="modal-body">
                        <div class="form-group">
                            {{Form::label('name', 'Package Folder Name(*): ', array('class' => 'control-label'))}}
                            {{Form::text('name', '', array('class' => 'form-control'))}}
                        </div>
                        <div class="form-group">
                            {{Form::label('language', 'Language: ', array('class' => 'control-label'))}}
                            {{Form::select('language', InputHelper::$language_availables, 'en', array('class' => 'form-control'))}}
                        </div>
                        <div class="row">
                            <div class="modal-header"><h4 class="modal-title">Package Name</h4></div>
                            @foreach(InputHelper::$language_availables as $language)
                                <div class="form-group col-lg-6">
                                    {{Form::label('language_name', $language, array('class' => 'control-label'))}}
                                    {{Form::text('language_name['. $language .']', '', array('class' => 'form-control'))}}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" data-loading-text="Creating..." onclick="StickerPackage.createPackage(this,'main'); return false;">Save</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop
