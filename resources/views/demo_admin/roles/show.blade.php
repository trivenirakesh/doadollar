@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <span>{{ $module }}</span>
                        <div>
                            <a class="btn btn-sm btn-dark" href="{{route('admin.roles.index')}}">Back</a>
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div class="card" style="width: 70%;">
                        <div class="card-body">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item"><b>Name :</b> {{$role->name}} </li>
                                <li class="list-group-item"><b>Created At :</b> {{$role->created_at}}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection