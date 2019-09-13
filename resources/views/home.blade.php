@extends('layouts.app')

@section('content')
<div class="container">
    <dashboard-statistic :postdata="{{json_encode($post_data)}}"></dashboard-statistic>
</div>
@endsection
