@extends('layouts.site')

@section('content')
  <div class="container py-4">
    <h1 class="mb-4">{{ $page->title }}</h1>

    {!! $page->content !!}
  </div>
@endsection
