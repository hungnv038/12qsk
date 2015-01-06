<table class="table table-bordered table-hover table-striped">
    <tr>
        <th style="width: 10%;">From Name</th>
        <th>Avatar</th>
        <th>Content</th>
        <th>Photo</th>
        <th style="width: 10%;">Updated</th>
        <th style="width: 8%;">Action</th>
    </tr>
    @foreach($messages as $message)
        <tr>
            <td>{{$message->from_name}}</td>
            <td><img src="{{$message->avatar}}" height="50"/></td>
            <td>{{$message->content}}</td>
            <td style="width: 10%;"><img src="{{$urlPhoto . $message->photo}}" height="50"/></td>
            <td style="text-align: center;">{{$message->updated_at}}</td>
            <td><span class="btn btn-primary" onclick="openPhoto(this, {{$message->id}}, '{{$params['version']}}');">Open</span></td>
        </tr>
    @endforeach
</table>