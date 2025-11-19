@extends('layouts.admin-spa')

@section('title', __('admin.dashboard.title'))

@section('spa-content')
    @include('admin.partials.dashboard-content')
@endsection
