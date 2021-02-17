@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Student</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('students.index') }}" title="Go back">Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('students.store') }}" method="POST" >
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Student Name:</strong>
                    <input type="text" name="student_name" class="form-control" placeholder="Name"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Contact Number:</strong>
                    <input type="number" name="contact_number" class="form-control" placeholder="Contact #"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Email Address</strong>
                    <input type="email" name="email_address" class="form-control" placeholder="Email">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Address:</strong>
                    <textarea name="address" class="form-control" placeholder="Address"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Parent 1:</strong>
                    <select name="parent_1" class="form-control">
                        <option value="" selected></option>
                        @foreach($parents as $p){
                            <option value="{{ $p->prent_id }}">{{ $p->parent_name }}</option>
                        }
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Parent 2:</strong>
                    <select name="parent_2" class="form-control">
                        <option value="" selected></option>
                        @foreach($parents as $p){
                            <option value="{{ $p->prent_id }}">{{ $p->parent_name }}</option>
                        }
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                <strong>Courses:</strong><br/>
                <div class="form-check-inline">
                <label class="form-check-label">
                    <input name="courses[]" type="checkbox" value="math_1" class="form-check-input">Basic Math
                </label>
                </div>
                <div class="form-check-inline">
                <label class="form-check-label">
                    <input name="courses[]" type="checkbox" value="math_2" class="form-check-input">Advanced Math
                </label>
                </div>
                <div class="form-check-inline">
                <label class="form-check-label">
                    <input name="courses[]" type="checkbox" value="math_3" class="form-check-input">Advanced++ Math
                </label>
                </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
    </div>
@endsection