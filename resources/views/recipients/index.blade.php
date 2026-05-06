@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>Recipients</h2>
    <a class="button" href="{{ route('recipients.create') }}">Add Recipient</a>
  </div>

  <section class="panel">
    <table>
      <thead><tr><th>Email</th><th>Name</th><th>Organization</th><th>Consent</th><th>Status</th></tr></thead>
      <tbody>
        @foreach ($recipients as $recipient)
          <tr>
            <td><a href="{{ route('recipients.show', $recipient) }}">{{ $recipient->email }}</a></td>
            <td>{{ $recipient->displayName() }}</td>
            <td>{{ $recipient->organization }}</td>
            <td>{{ optional($recipient->consented_at)->format('Y-m-d') ?? 'Missing' }}</td>
            <td>{{ $recipient->suppressed_at || $recipient->unsubscribed_at || $recipient->hard_bounced_at ? 'Blocked' : 'Deliverable' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $recipients->links() }}
  </section>
@endsection
