@extends('layouts.app')

@section('content')
  <div class="topline"><h2>New Segment</h2></div>
  <form class="panel" method="post" action="{{ route('groups.store') }}">
    @csrf
    <label>Name <input name="name" required value="{{ old('name') }}"></label>
    <label>Description <textarea name="description">{{ old('description') }}</textarea></label>
    <button type="submit">Create Segment</button>
  </form>
@endsection
