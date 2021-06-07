<td>
@if($content->type->id == 1)
    {!! $content->path !!}
@elseif($content->type->id == 2)
    {{ $content->path }}
@elseif($content->type->id == 3)
    <img src="{{ $content->path }}" alt="" style="width:250px" height="200px">
@elseif($content->type->id == 4)
    <audio controls src="{{ $content->path }}" style="width:100%"></audio>
@elseif($content->type->id == 5)
    <video src="{{ $content->path }}" style="width:250px;height:200px" height="200px" controls poster="{{ $content->image_preview }}"></video>'
@elseif($content->type->id == 6)
<iframe src="{{ $content->path  }}" width="250px" height="200px"></iframe>
@elseif($content->type->id == 7)
    <a href="'.$content->path.'">{{ $content->path }}</a>
@endif
</td>
