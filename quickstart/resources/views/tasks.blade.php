@extends('layouts.app')

@section('content')
    <div class="panel-body">
        <!-- display validation errors -->
        @include('common.errors')
        <!-- NEW Task Form -->
        <form action="/task" method="post" form-horizontal">
            {{ csrf_field() }}
            <!-- Task Name -->
            <div class="form-group">
                <label for="task" class="col-sm-3 control-label">Task</label>
                <div class="col-sm-6">
                    <input type="text" name="name" id="task-name" class="form-control"/>
                    
                </div>
            </div>
            <!-- Add Task Button -->
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-default">
                        <i class="fa fa-plus"></i>Add Task
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- TODO:Current Tasks -->
    @if ( count($tasks) >0 )
        <div class="panel panel-default">
            <div class="panel-heading">
                Current Tasks
            </div>
            <div class="panle-body">    
                <table class="table table-striped task-table">
                    <thead>
                        <th>Task</th>
                        <th>&nbsp;</th>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td class="table-text">
                            <div>
                                {{ $task->name }}
                            </div>
                            </td>
                            <td>
                            <!-- TODO:Delete Button -->
                                <form action="/task/{{ $task->id }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button>Delete Button</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection