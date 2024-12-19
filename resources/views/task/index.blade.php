<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5 p-3">
        <h1 class="text-center mb-4">Task Management</h1>
        @if (session()->has('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
            <li>
                {{$error}}
            </li>
            @endforeach
        </div>
        @endif
        <form action="{{route('task.store')}}" method="POST">

            <div class="card">
                <div class="card-header">Add New Task</div>
                <div class="card-body">
                    @csrf
                    <div class="mb-3">
                        <label for="taskName" class="form-label">Task Name</label>
                        <input type="text" class="form-control" name="name" id="taskName" placeholder="Task Name">
                    </div>
                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="taskDescription" placeholder="Task Description" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </div>
            </div>
        </form>


        <h3 class="pt-4">Task List</h3>
        <div class="card-body">
            <div>
                <div class="card mt-4">
                    @if ($task->isEmpty())
                    <p>
                        <center>No task available</center>
                    </p>
                    @else
                    @foreach ($task as $t)

                    <div class="row px-3 py-2 mt-2">
                        <div class="col-10">
                            <p><strong>{{$t->name}}</strong></p>
                            <p>{{$t->description}}</p>
                        </div>
                        <div class="col-2 d-flex align-items-center justify-content-end">
                            @if ($t->status == 'pending')
                            <div class="badge text-bg-warning text-white fs-5">Pending</div>
                            @elseif ($t->status == 'completed')
                            <div class="badge text-bg-success text-white fs-5">Completed</div>
                            @endif
                        </div>
                        <!-- <div class="col-3 d-flex align-items-center ">
                            <a href="{{route('task.edit', ['task'=>$t])}}" class="btn btn-primary ms-auto">Edit Tugas</a>
                            <form method="post" action="{{route('task.delete', ['task'=>$t])}}">
                                @csrf
                                @method('delete')
                                <input type="submit" class="btn btn-danger" value="Delete">
                            </form>
                        </div> -->
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo3
</body>

</html>
