@extends('layouts.app')
@section('title', 'Tag - ' . $tag->name)
@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="list-group">
                <a class="list-group-item active"><i class="fa fa-tag"></i> {{ $tag->name }} -
                    {{ plural('task', $tasks->total()) }}
                </a>
                @foreach($tasks as $task)
                    <a class="list-group-item" href="{{ route('member::tasks.show', [$authUser, $task]) }}">
                        <span class="badge">
                            {{ humans_time($task->created_at) }}
                        </span>
                        {{ $task->title }}
                    </a>
                @endforeach
            </div>
            {!! render_pagination($tasks) !!}
        </div>
    </div>
@stop