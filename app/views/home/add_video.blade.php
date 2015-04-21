@extends('admin.layouts.home')
@section('header')
    <title>Add new Video</title>

@stop
@section('content')
    <ul class="nav nav-tabs">
        <li class="active"><a href="#youtube_video" data-toggle="tab"><span class="glyphicon glyphicon-certificate"></span> Youtube Video </a></li>
        <li><a href="#link_video" data-toggle="tab"><span class="glyphicon glyphicon-bell"></span> Video from link </a></li>
    </ul>
    <div class="tabbable">
        <div class="tab-content">
            <div class="tab-pane active" id="youtube_video">
                <div id="div_youtube" style="padding-top: 10px;">
                    <div class="alert alert-success hidden" id="div_result1"> Success </div>
                    <form role="form">
                        <div class="form-group">
                            <label class="control-label" for="chanel_id_youtube">Chanel :</label>
                                <select id="chanel_id_youtube"
                                        class="form-control">
                                    @foreach($groups as $group)
                                        @foreach($group->chanels as $chanel)
                                            <option value="{{$chanel->id}}">{{$group->name}}_{{$chanel->name}}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Youtube Video Id:</label>
                            <input type="text" class="form-control" id="video_id_youtube" placeholder="Enter youtube video id" required>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Title:</label>
                            <input type="text" class="form-control" id="title_youtube" placeholder="Enter video title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" rows="5" id="description_youtube" placeholder="Enter video description"></textarea>
                        </div>
                        <button type="submit" id="btnSave_youtube" class="btn btn-primary" onclick="HomeModule.addNewYoutubeVideo(this); return false;">Save</button>
                    </form>
                </div>

            </div>
            <div class="tab-pane" id="link_video">
                <div id="div_link_video" style="padding-top: 10px;">
                    <div class="alert alert-success hidden" id="div_result2"> Success </div>
                    <form role="form">
                        <div class="form-group">
                            <label class="control-label" for="chanel_id_link">Chanel :</label>
                            <select id="chanel_id_link"
                                    class="form-control">
                                @foreach($groups as $group)
                                    @foreach($group->chanels as $chanel)
                                        <option value="{{$chanel->id}}">{{$group->name}}_{{$chanel->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="email">Video link:</label>
                            <input type="text" class="form-control" id="video_id_link" placeholder="Enter video link" required>
                        </div>
                        <div class="form-group">
                            <label for="pwd">Title:</label>
                            <input type="text" class="form-control" id="title_link" placeholder="Enter video title" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" rows="5" id="description_link" placeholder="Enter video description"></textarea>
                        </div>
                        <button type="submit" id="btnSave_link" class="btn btn-primary" onclick="HomeModule.addNewVideoLink(this); return false;">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
@section('footer')
    <script src="<?php echo URL::to('/') ?>/home/js/homeModule.js"></script>
@stop