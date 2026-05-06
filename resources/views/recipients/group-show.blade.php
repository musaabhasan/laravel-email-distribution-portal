@extends('layouts.app')

@section('content')
  <div class="topline"><h2>{{ $group->name }}</h2></div>
  <section class="panel">
    <p>{{ $group->description }}</p>
    <p><strong>Recipients:</strong> {{ number_format($group->recipients_count) }}</p>
  </section>
  <section class="panel">
    <table>
      <thead><tr><th>Email</th><th>Name</th><th>Organization</th></tr></thead>
      <tbody>
        @foreach ($recipients as $recipient)
          <tr><td>{{ $recipient->email }}</td><td>{{ $recipient->displayName() }}</td><td>{{ $recipient->organization }}</td></tr>
        @endforeach
      </tbody>
    </table>
    {{ $recipients->links() }}
  </section>
@endsection
