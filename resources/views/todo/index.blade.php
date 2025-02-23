<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .checkbox-lg {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    
        .checkbox-lg .form-check-input {
            transform: scale(1.4);
            margin-right: 0.4rem;
        }
    </style>
    
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <h2 class="text-center mb-2 mt-2">To-Do List</h2>
            <div class="card-header mt-3">
                <button class="btn btn-outline-secondary" id="show-all">Show All Tasks</button>
                <a class="btn btn-outline-secondary" href="{{asset("/")}}"><i class="fa fa-repeat"></i></a>
            </div>
            <div class="card-header ">
                <form id="task-form">
                    <div class="input-group">
                        <input type="text" id="task-input" class="form-control" placeholder="Project #To Do">
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                    <small class="text-danger" id="error-msg"></small>
                </form>
            </div>

            <div class="card-body">
                <div class="table table-responsive">
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th width="80px"></th>
                                <th class="text-center">Todo Project Name</th>
                                <th class="text-center" width="100px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="task-list">
                            @foreach($todos as $task)
                                <tr class="task-item {{ $task->is_completed ? 'd-none' : '' }}" data-id="{{ $task->id }}">
                                    <td>
                                        <div class="form-check checkbox-lg">
                                            <input type="checkbox" class="mark-complete form-check-input" {{ $task->is_completed ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <span>{{ $task->task }}  <span style="margin-left: 4rem;">{{$task->created_at->format('d-m-Y H:i:s')}}</span></span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm delete-task"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Script using jquery and ajax --}}
    <script>
        $(document).ready(function() {
            // Below code is for adding new todo task and stoping reload or referesh
            $('#task-form').submit(function(e) {
                e.preventDefault();
                let task = $('#task-input').val().trim();
                if (!task) return $('#error-msg').text('Task cannot be empty.');

                $.post("{{ url('/tasks') }}", { task: task, _token: "{{ csrf_token() }}" }, function(response) {
                    location.reload();
                }).fail(function(xhr) {
                    $('#error-msg').text(xhr.responseJSON.message);
                });
            });

            // This is for mark the todo task as compeleted 
            $('.mark-complete').change(function() {
                let taskItem = $(this).closest('.task-item');
                let taskId = taskItem.data('id');

                $.ajax({
                    url: "/tasks/" + taskId,
                    type: "PATCH",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function() {
                        taskItem.addClass('d-none'); // It will hide our task if we click on checkbox for complete todo task
                    }
                });
            });

            // Delete Task with Confirmation
            $('.delete-task').click(function() {
                let taskItem = $(this).closest('.task-item');
                let taskId = taskItem.data('id');

                if (confirm("Are you sure you want to delete this todo task?")) {
                    $.ajax({
                        url: "/tasks/" + taskId,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function() {
                            taskItem.remove();
                        }
                    });
                }
            });

            // It will Show all the Task both compeleted and not compeleted
            $('#show-all').click(function() {
                $('.task-item').removeClass('d-none');
            });
        });
    </script>
</body>
</html>
