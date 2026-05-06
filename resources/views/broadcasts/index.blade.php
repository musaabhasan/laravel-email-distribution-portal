@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>Broadcasts</h2>
    <a class="button" href="{{ route('broadcasts.create') }}">New Broadcast</a>
  </div>
  <section class="panel">
    <table>
      <thead><tr><th>Name</th><th>Status</th><th>Queued</th><th>Scheduled</th><th>Owner</th></tr></thead>
      <tbody>
        @foreach ($broadcasts as $broadcast)
          <tr>
            <td><a href="{{ route('broadcasts.show', $broadcast) }}">{{ $broadcast->name }}</a></td>
            <td><span class="badge">{{ $broadcast->status->value }}</span></td>
            <td>{{ number_format($broadcast->recipients_count) }}</td>
            <td>{{ optional($broadcast->scheduled_at)->format('Y-m-d H:i') ?? 'Immediate' }}</td>
            <td>{{ $broadcast->user_id }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $broadcasts->links() }}
  </section>
@endsection
