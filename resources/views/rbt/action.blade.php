<td class="visible-md visible-lg">
    <div class="btn-group">
        <a class="btn btn-sm show-tooltip" href="{{url("rbt/".$value->id."/edit")}}" title="Edit"><i class="fa fa-edit"></i></a>
        <a class="btn btn-sm show-tooltip btn-danger" onclick="return ConfirmDelete();" href="{{url("rbt/".$value->id."/delete")}}" title="Delete"><i class="fa fa-trash"></i></a>
    </div>
</td>
