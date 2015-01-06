@extends('admin.layouts.default')
@section('content')
<link href="<?php echo URL::to('/') ?>/admin/css/bootstrap-treeview.css" rel="stylesheet"/>
<script src="<?php echo URL::to('/') ?>/admin/js/bootstrap-treeview.js"></script>
<script>
	var urlApi = '<?php echo URL::to('/') ?>/';
	function getPackageItems(package_id) {
	    $.ajax({
            url: urlApi + 'getPackageItems',
            data: 'package_id='+ package_id,
            dataType: 'json',
            type: "GET",
            beforeSend: function() {
                $('#result').html('Loading...');
            },
            success:function(result) {
                $('#result').html(result);
            },
            error: function(jqXHR){
                $('#result').html(jqXHR.responseText);
            }
        });
	}

    /**
     * Edit item package
     * @param obj
     * @param item_id
     */
	function editItem(obj, id) {
        $(obj).html('Editing');
         $.ajax({
             url: urlApi + 'editPackageItem',
             data: 'id='+ id +'&type=edit',
             dataType: 'json',
             type: "POST",
             beforeSend: function() {
                 $('#result').html('Edit Item...');
             },
             success:function(result) {
                 $('#result').html(result);
             },
             error: function(jqXHR){
                 $('#result').html(jqXHR.responseText);
             }
         });
	}

	$(function(){
        $('#treeview').treeview({
            expandIcon: "fa fa-plus-circle",
            collapseIcon: "fa fa-minus-circle",
            nodeIcon: "fa fa-pagelines",
            showBorder: true,
            showTags: true,
            enableLinks: true,
            onNodeSelected: function(event, node) {
                getPackageItems(node.data.package_id);
            },
            levels: 2,
            data: <?php echo json_encode($packages) ?>
        });
	});
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-body">
                <a href="<?php echo URL::to('/createPackage') ?>?type=create_sub" class="btn btn-success" onclick="return confirm('Are you sure you want to create Sub Package?');">Create Sub Package</a>
                <a href="<?php echo URL::to('/createPackage') ?>?type=create_package" class="btn btn-primary" onclick="return confirm('Are you sure you want to create Package?');">Create Package</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Packages</h3>
            </div>
        </div>
        <div id="treeview" class="box-body table-responsive no-padding"></div>
    </div>
    <div class="col-lg-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Package Items</h3>
            </div>
        </div>
        <div id="result" class="box-body table-responsive no-padding"></div>
	</div>
</div>
@stop