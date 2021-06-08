<td class="visible-md visible-lg">
<div class="btn-group">
    <a class="btn btn-sm btn-success show-tooltip" title="Add Post" href="{{url("post/create?content_id=".$value->id."&title=".$value->title)}}" data-original-title="Add Post"><i class="fa fa-plus"></i></a>
    @if($value->operators_count > 0)
    <a class="btn btn-sm show-tooltip" title="Show Posts" href="{{url("post?content_id=$value->id")}}" data-original-title="show Posts"><i class="fa fa-step-forward"></i></a>
    @endif
    <a class="btn btn-sm show-tooltip" href="{{url("content/$value->id/edit")}}" title="Edit"><i class="fa fa-edit"></i></a>
    <a class="btn btn-sm show-tooltip btn-danger" onclick="return ConfirmDelete();" href="{{url("content/$value->id/delete")}}" title="Delete"><i class="fa fa-trash"></i></a>
    @if($value->type->id == 4)
    <a class="btn btn-sm btn-info show-tooltip" title="Add Rbt" href="{{url("rbt/create?content_id=".$value->id."&title=".$value->title)}}" data-original-title="Add RBt"><i class="fa fa-plus"></i></a>
    @endif
    @if($value->rbt_operators_count > 0)
    <a class="btn btn-sm show-tooltip" title="Show Rbt Code" href="{{url("rbt?content_id=$value->id")}}" data-original-title="show RBt_code"><i class="fa fa-step-forward"></i></a>
    @endif
</div>
</td>

