@if($alert['error'] == 1)
    <div class="alert alert-danger alert-dismissable">{{$alert['msg']}}</div>
@elseif($alert['error'] == 0)
    <div class="alert alert-success">{{$alert['msg']}}</div>
@endif