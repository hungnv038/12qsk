/**
 *
 *	Developed by Hieutrieu
 */
//(function(window, $) {
var StickerPackage = {
    group_package: '#group_package',
    group_package_items: '#group_package_items',
    package_model: $('#createPackageModel'),
    container: $('#container_layout'),
    urlApi: '/',

    package_id: 0,
    sub_id: 0,
    totalItem: 0,
    platform: '',
    version: '',

    setUp: function() {
        StickerPackage.getPackages();
        StickerPackage.validateFormCreatePackage();
    },
    getPackages: function() {;
        var that = this;
        $('#msg_alert').empty();
        $.ajax({
            url: that.urlApi + 'package/loadpackages',
            data: 'package_id='+ that.package_id,
            dataType: 'html',
            type: "GET",
            beforeSend: function() {
                $('#package_loading').button('loading');
            },
            success:function(result) {
                $(that.group_package).html(result);
                $('#package_loading').button('reset');
            },
            error: function(jqXHR){
                $(that.group_package).html(result);
            }
        });
    },
    getPackageItems: function(myself, package_id) {
        var that = this;
        $('#msg_alert').empty();
        if(myself !== 'noactive') {
            $(that.group_package).find('a').removeClass('active');
            $(myself).addClass('active');
        }
        if(package_id === undefined) {
            if(parseInt(that.package_id) <= 0 || parseInt(that.package_id) === NaN) {
                alert('You must choose a package.');
                return false;
            }
        } else if(package_id > 0) {
            that.package_id = package_id;
        }
        that.platform = $('#platform').val();
        that.version = $('#version').val();
        //that.controlSubButton();
        $.ajax({
            url: that.urlApi + 'package/getpackageitems',
            data: 'package_id='+ that.package_id +'&platform='+ that.platform +'&version='+ that.version,
            type: "GET",
            beforeSend: function() {
                $(that.group_package_items).html('Loading...');
                $('#package_items_header select').removeAttr('disabled');
                $('#package_items_header button').removeAttr('disabled');
            },
            success:function(result) {
                $(that.group_package_items).html(result.html);
                that.sub_id = result.sub_id;
                that.totalItem = result.totalItem;
                that.controlSubButton();
            },
            error: function(jqXHR){
            }
        });
    },
    editItem: function (obj, item_id, type) {
        var that = this;
        $('#msg_alert').empty();
        $.ajax({
            url: that.urlApi + 'package/editpackageitem',
            data: 'item_id='+ item_id +'&type='+ type +'&platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id,
            dataType: 'html',
            type: "POST",
            beforeSend: function() {
                $(obj).button('loading');
                $(that.group_package_items).html('Loading...');
                $('#package_items_header select').attr('disabled','disabled');
                $('#package_items_header button').attr('disabled','disabled');
            },
            success:function(result) {
                $('#group_package_items').html(result);
                $(obj).button('reset');
            },
            error: function(jqXHR){
                $('#package_items_header select').removeAttr('disabled');
                $('#package_items_header button').removeAttr('disabled');
                $('#group_package_items').html(jqXHR.responseText);
            }
        });
    },
    saveItem: function(obj) {
        var that = this;
        $('#msg_alert').empty();
        var form = $('form')[0]; // You need to use standart javascript object here
        var formData = new FormData(form);
        $.ajax({
            url: that.urlApi + 'package/editpackageitem?type=save',
            data: formData,
            type: "POST",
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(obj).button('loading');
            },
            success:function(result) {
                $('#group_package_items').html(result);
                $('#package_items_header select').removeAttr('disabled');
                $('#package_items_header button').removeAttr('disabled');
                //$('#package_sub_bottom_controlbutton').show();
                StickerPackage.getPackageItems('noactive');
                $(obj).button('reset');
            },
            error: function(jqXHR){
                $('#package_items_header select').removeAttr('disabled');
                $('#package_items_header button').removeAttr('disabled');
                $(obj).button('reset');
            }
        });
    },
    moveTop: function (obj, item_id, type) {
        var that = this;
        $('#msg_alert').empty();
        $.ajax({
            url: that.urlApi + 'package/moveTop',
            data: 'item_id='+ item_id +'&type='+ type +'&platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id,
            dataType: 'html',
            type: "POST",
            beforeSend: function() {
                $(obj).button('loading');
            },
            success:function(result) {
                StickerPackage.getPackageItems('noactive');
                $(obj).button('reset');
            },
            error: function(jqXHR){
            }
        });
    },
    saveFinalItems: function(myself) {
        var that = this;
        $('#msg_alert').empty();
        $.ajax({
            url: that.urlApi + 'package/savepackageitemfinal',
            data: 'platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id +'&sub_id='+ that.sub_id,
            type: "GET",
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                $(myself).button('reset');
                StickerPackage.getPackages();
                StickerPackage.getPackageItems('noactive');
                $('#msg_alert').html(result.html);
            },
            error: function(jqXHR){

            }
        });
    },
    createPackage: function(myself, type) {
        var that = this;
        $('#msg_alert').empty();
        var formData = '';
        if(type == 'main') {
            name = $('#createPackageFrom').find('#name').val();
            var form = $('form')[0]; // You need to use standart javascript object here
            formData = new FormData(form);
            $('#createPackageFrom').data('bootstrapValidator').validate();
            if(name == '') return false;
        } else {
            formData = new FormData();
            formData.append("package_id", that.package_id);
            formData.append("platform", that.platform);
            formData.append("version", that.version);
        }
        $.ajax({
            url: that.urlApi + 'package/createpackage?type='+ type,
            data: formData,
            type: "POST",
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                if(type == 'sub') {
                    that.getPackages();
                    that.getPackageItems('noactive');
                } else if(type == 'main') {
                    that.getPackages();
                    $('#createPackageModel').modal('hide')
                }
                $('#msg_alert').html(result.html);
                $(myself).button('reset');
            },
            error: function(jqXHR){
                //$('#result').html(jqXHR.responseText);
            }
        });
        return false;
    },
    downloadZipPackage: function(myself) {
        var that = this;
        $('#msg_alert').empty();
        window.location.href = that.urlApi + 'package/downloadzippackage?platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id;
    },
    downloadFullZipPackage: function(myself) {
        var that = this;
        $('#msg_alert').empty();
        window.location.href = that.urlApi + 'package/downloadfullzippackage?platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id;
    },
    uploadZipToS3: function(myself) {
        var that = this;
        $('#msg_alert').empty();
        $.ajax({
            url: that.urlApi + 'package/uploadziptos3',
            data: 'platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id,
            type: "GET",
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                $(myself).button('reset');
                $('#msg_alert').html(result.html);
            },
            error: function(jqXHR){
                //$('#result').html(jqXHR.responseText);
            }
        });
    },
    showImage: function(fileInput, show_id) {
        var files = fileInput.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageType = /image.*/;
            if (!file.type.match(imageType)) {
                continue;
            }
            var img = document.getElementById(show_id);
            img.file = file;
            var reader = new FileReader();
            reader.onload = (function(aImg) {
                return function(e) {
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        }
    },
    controlSubButton: function() {
        var that = this;
        if(that.package_id > 0) {
            if(that.totalItem > 0) {
                $('#allow_save').show();
                $('#allow_upload').show();
            } else {
                $('#allow_save').hide();
                $('#allow_upload').hide();
            }
            if (that.sub_id == 0) {
                $('#allow_add').hide();
                $('#allow_create').show();
                $('#allow_save').hide();
            } else {
                $('#allow_upload').hide();
                $('#allow_create').hide();
                $('#allow_add').show();
            }

        } else {
            $('#allow_add').hide();
            $('#allow_create').hide();
            $('#allow_save').hide();
            $('#allow_upload').hide();
        }
    },
    validateFormCreatePackage: function() {
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
                            message: 'The Folder Name is required'
                        }
                    }
                }
            }
        });
    },
    cloneItem: function(myself) {
        var that = this;
        var item_ids = $('.checkAll:checked').map(function() {
            return $(this).val();
        }).get();
        $.ajax({
            url: that.urlApi + 'package/cloneItems',
            data: 'platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id +'&item_ids='+ item_ids,
            type: "POST",
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                alert(result);
                $(myself).button('reset');
                $('#msg_alert').html(result.html);
            },
            error: function(jqXHR){
                //$('#result').html(jqXHR.responseText);
            }
        });
    },
    saveSortedItems: function(myself) {
        var that = this;
        var item_ids = $('.checkAll').map(function() {
            return $(this).val();
        }).get();
        $.ajax({
            url: that.urlApi + 'package/saveSortedItems',
            data: 'platform='+ that.platform +'&version='+ that.version +'&package_id='+ that.package_id +'&item_ids='+ item_ids,
            type: "POST",
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                StickerPackage.getPackageItems('noactive');
                $(myself).button('reset');
            },
            error: function(jqXHR){
                //$('#result').html(jqXHR.responseText);
            }
        });
    },
    importPackage: function(myself) {
        var that = this;
        $.ajax({
            url: that.urlApi + 'package/import',
            data: '',
            type: "GET",
            beforeSend: function() {
                $(that.group_package_items).html('Loading...');
                $(myself).button('loading');
            },
            success:function(result) {
                $(that.group_package_items).html(result.html);
                $(myself).button('reset');
            },
            error: function(jqXHR){
                $(myself).button('reset');
            }
        });
    },
    saveImportItem: function(myself) {
        var that = this;
        $('#msg_alert').empty();
        var form = $('form')[0]; // You need to use standart javascript object here
        var formData = new FormData(form);
        $.ajax({
            url: that.urlApi + 'package/import',
            data: formData,
            type: "POST",
            contentType: false,
            processData: false,
            beforeSend: function() {
                $(myself).button('loading');
            },
            success:function(result) {
                $(myself).button('reset');
                $(that.group_package_items).html(result.html);
                setTimeout(function(){
                    window.location.reload(1);
                }, 3000);
            },
            error: function(jqXHR){
                $(myself).button('reset');
            }
        });
    }
};

$(function() {
    new StickerPackage.setUp();
    $('#createPackageModel').on('shown.bs.modal', function() {
        $('#createPackageFrom').bootstrapValidator('resetForm', true);
    });
});
//})(window, jQuery);
