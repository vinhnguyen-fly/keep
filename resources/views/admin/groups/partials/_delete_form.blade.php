{!! Form::open(['method' => 'DELETE', 'route' => ['admin::groups.active.delete', $group]]) !!}
    <button type="submit"
            class="btn btn-danger btn-circle btn-sm"
            data-toggle="tooltip"
            data-placement="bottom"
            title="Trash this group">
        <i class="fa fa-times"></i>
    </button>
{!! Form::close() !!}