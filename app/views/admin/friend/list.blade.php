<div class="row">
    <div class="col-lg-12">
        <table class="table table-hover">
            <tr>
                <th style="width:20%;text-align:right;">User Token</th>
                <th style="width:30%;text-align:right;">User</th>
                <th style="width:10%;text-align:center;">Counter</th>
                <th style="width:30%;text-align:left;">Friend</th>
                <th style="width:20%;text-align:left;">Friend Token</th>
            </tr>
        @foreach($users as $user)
            <tr>
                <td style="width:20%;text-align:right;">
                    <div>{{$user->friend_access_token}}</div>
                </td>
                <td style="width:30%;text-align:right;">
                    <img src="{{$user->user_avatar}}" class="margin" height="50"/>
                    <div>{{$user->user_name}}</div>
                </td>
                <td style="width:10%;text-align:center; vertical-align: middle">
                    <div class="btn btn-danger">{{$user->counter}}</div>
                </td>
                <td style="width:30%;text-align:left;">
                    <img src="{{$user->friend_avatar}}" class="margin" height="50"/>
                    <div>{{$user->friend_name}}</div>
                </td>
                <td style="width:20%;text-align:left;">
                    <div>{{$user->friend_access_token}}</div>
                </td>
            </tr>
         @endforeach
        </table>
    </div>
</div>