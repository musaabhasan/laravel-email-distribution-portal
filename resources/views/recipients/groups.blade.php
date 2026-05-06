@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>Segments</h2>
    <a class="button" href="{{ route('groups.create') }}">New Segment</a>
  </div>
  <section class="panel">
    <table>
      <thead><tr><th>Name</th><th>Description</th><th>Recipients</th></tr></thead>
      <tbody>
        @foreach ($groups as $group)
          <tr>
            <td><a href="{{ route('groups.show', $group) }}">{{ $group->name }}</a></td>
            <td>{{ $group->description }}</td>
            <td>{{ number_format($group->recipients_count) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>
@endsection
