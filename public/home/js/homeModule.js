var HomeModule = {
    urlApi: '/live',
    addNewYoutubeVideo : function (myself) {
        var video_id=$('#video_id_youtube').val();
        var title=$('#title_youtube').val();
        var description=$('#description_youtube').val();
        var group_chanel=$('#chanel_id_youtube').val();

        console.log(group_chanel);

        $.ajax({
            url: this.urlApi+'/service/videos',
            data: {type:1,video_id:video_id,title:title,description:description,chanel_id:group_chanel},
            type : "POST",
            beforeSend: function () {
                $("#btnSave_youtube").html('Saving...');
            },
            success: function (result) {
                $("#btnSave_youtube").html('Save');
                if(result.error==0) {
                    $("#div_result1").html("Success");
                    $('#div_result1').removeClass();
                    $('#div_result1').addClass("alert");
                    $('#div_result1').addClass("alert-success");
                } else {
                    $("#div_result1").html(result.html);
                    $('#div_result1').removeClass();
                    $('#div_result1').addClass("alert");
                    $('#div_result1').addClass("alert-danger");
                }

            },
            error: function (jqXHR) {
                $("#btnSave_youtube").html('Save');
                $("#div_result1").html("Error happened");
                $('#div_result1').removeClass();
                $('#div_result1').addClass("alert");
                $('#div_result1').addClass("alert-danger");
            }
        });
    },
    addNewVideoLink : function (myself) {
        var video_id=$('#video_id_link').val();
        var title=$('#title_link').val();
        var description=$('#description_link').val();
        var group_chanel=$('#chanel_id_link').val();

        $.ajax({
            url: this.urlApi+'/service/videos',
            data: {type:2,video_id:video_id,title:title,description:description,chanel_id:group_chanel},
            type : "POST",
            beforeSend: function () {
                $("#btnSave_link").html('Saving...');
            },
            success: function (result) {
                $("#btnSave_link").html('Save');
                if(result.error==0) {
                    $("#div_result2").html("Success");
                    $('#div_result2').removeClass();
                    $('#div_result2').addClass("alert");
                    $('#div_result2').addClass("alert-success");
                } else {
                    $("#div_result2").html(result.html);
                    $('#div_result2').removeClass();
                    $('#div_result2').addClass("alert");
                    $('#div_result2').addClass("alert-danger");
                }

            },
            error: function (jqXHR) {
                $("#btnSave_link").html('Save');
                $("#div_result2").html("Error happened");
                $('#div_result2').removeClass();
                $('#div_result2').addClass("alert");
                $('#div_result2').addClass("alert-danger");
            }
        });
    }

};