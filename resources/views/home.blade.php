{{-- @extends('main.chat')
@section('chat') --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.index') }}" class="btn btn-primary">Admin Dashboard</a>
                    @else
                        {{ __('Dashboard') }}
                    @endif
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- @endsection --}}