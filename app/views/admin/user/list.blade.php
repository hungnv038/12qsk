<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th>Avatar</th>
                    <th style="width: 20%;">Name</th>
                    <th>Facebook Id</th>
                    <th>Access Token</th>
                    <th>Facebook Token</th>
                    <th>Email</th>
                    <th style="width: 8%;">Type User</th>
                    <th style="width: 8%;">Service</th>
                </tr>
                @foreach($users as $user)
                    <tr class="">
                        <td style="text-align: center;"><img src="{{$user->avatar}}" height="40"/></td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->fb_id}}</td>
                        <td><p style="width: 100px;word-wrap: break-word;">{{$user->access_token}}</p></td>
                        <td><p style="width: 300px;word-wrap: break-word;">{{$user->fb_token}}</p></td>
                        <td><p style="width: 100px;word-wrap: break-word;">{{$user->email}}</p></td>
                        <td style="text-align: center;">{{$user->type_user}}</td>
                        <td style="text-align: center;">{{$user->service}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>