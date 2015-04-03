{!! Form::open(['route' => ['users.tasks.destroy', $user->slug, $task->slug], 'method' => 'DELETE']) !!}
    <a class="task-action" data-toggle="modal" data-target="#delete-task-modal">
        <i class="fa fa-trash-o"></i>Delete
    </a>
{!! Form::close() !!}

<div class="modal fade" id="delete-task-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this task? Once your task is deleted, it cannot be recovered. Be careful!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm">Confirm</button>
            </div>
        </div>
    </div>
</div>