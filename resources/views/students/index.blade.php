@extends('layouts.app')

@section('content')
<div class="container">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Student Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('students.create') }}" title="Create a project"> <i class="fas fa-plus-circle">ADD STUDENT</i>
                    </a>
            </div>
        </div>

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        
        <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>#</th>
            <th>Student ID</th>
            <th>Name</th>
            <th>Contact Number</th>
            <th>Email Address</th>
            <th>Address</th>
            <th>Parents</th>
            <th>Enrolled Courses</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>

        @for($x = 0; $x <= sizeof($student)-1; $x++)
            <tr>
                <td>{{ ($x+1) }}</td>
                <td>{{ $student[$x]['student_id']  }}</td>
                <td>{{ $student[$x]['student_name']  }}</td>
                <td>{{ $student[$x]['contact_number']  }}</td>
                <td>{{ $student[$x]['email_address']  }}</td>
                <td>{{ $student[$x]['address']  }}</td>
                <td>{{ $student[$x]['parents'] }}</td>
                <td>{{ $student[$x]['courses_enrolled'] }}</td>
                <td>
                    <a href="{{ route('students.edit', $student[$x]['student_id']) }}">
                        <button class="btn btn-warning">EDIT</button>
                    </a>
                </td>
                <td>
                    <form action="{{ route('students.destroy', $student[$x]['student_id']) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" class="btn btn-danger">DELETE
                        </button>
                    </form>
                </td>
            </tr>
        @endfor
      </table>
</div>




@endsection
