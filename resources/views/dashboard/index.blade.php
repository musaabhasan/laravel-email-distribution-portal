@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>Distribution Dashboard</h2>
    <a class="button" href="{{ route('broadcasts.create') }}">New Broadcast</a>
  </div>

  <section class="grid">
    <article class="panel metric"><span>Total recipients</span><strong>{{ number_format($recipientCount) }}</strong></article>
    <article class="panel metric"><span>Deliverable recipients</span><strong>{{ number_format($deliverableCount) }}</strong></article>
    <article class="panel metric"><span>Pending queue</span><strong>{{ number_format($pendingCount) }}</strong></article>
    <article class="panel metric"><span>Remaining allowance</span><strong>{{ number_format($remainingAllowance) }}</strong></article>
  </section>

  <section class="panel">
    <h3>Recent Broadcasts</h3>
    <table>
      <thead><tr><th>Name</th><th>Status</th><th>Scheduled</th><th>Created</th></tr></thead>
      <tbody>
        @foreach ($recentBroadcasts as $broadcast)
          <tr>
            <td><a href="{{ route('broadcasts.show', $broadcast) }}">{{ $broadcast->name }}</a></td>
            <td><span class="badge">{{ $broadcast->status->value }}</span></td>
            <td>{{ optional($broadcast->scheduled_at)->format('Y-m-d H:i') ?? 'Immediate' }}</td>
            <td>{{ $broadcast->created_at->format('Y-m-d H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </section>
@endsection
