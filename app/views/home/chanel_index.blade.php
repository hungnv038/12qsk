<?php
    Group::getInstance()->getGroupsChanels();
?>

@extends('admin.layouts.home')
@section('header')
    <title>Chanel Management</title>

@stop
@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Groups
                    <div class="btn-group navbar-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-small btn-sm btn-warning"  onclick=""><span
                                    class="fa fa-plus"></span> Add new
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3 col-lg-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Chanels
                    <div class="btn-group navbar-right" role="group" aria-label="..." >
                        <button type="button" class="btn btn-small btn-sm btn-warning" onclick=""><span
                                    class="fa fa-plus"></span> Add new
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-lg-6 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Videos
                    <div class="btn-group navbar-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-small btn-sm btn-warning" onclick=""><span
                                    class="fa fa-plus"></span> Add new
                        </button>
                    </div>
                </div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>

@stop
@section('footer')

@stop