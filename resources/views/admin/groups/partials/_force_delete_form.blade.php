{!! Form::open(['method' => 'DELETE', 'route' => ['admin.groups.trashed.delete', $group]]) !!}
    <button type="submit"
            class="btn btn-danger btn-xs"
            data-toggle="tooltip"
            data-placement="bottom"
            title="Permanently delete">
        <i class="fa fa-trash-o"></i>
    </button>
{!! Form::close() !!}