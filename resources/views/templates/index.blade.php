@extends('layouts.app')

@section('content')
  <div class="topline">
    <h2>Templates</h2>
    <a class="button" href="{{ route('templates.create') }}">New Template</a>
  </div>
  <section class="panel">
    <table>
      <thead><tr><th>Name</th><th>Subject</th><th>Variables</th><th>Status</th></tr></thead>
      <tbody>
        @foreach ($templates as $template)
          <tr>
            <td><a href="{{ route('templates.show', $template) }}">{{ $template->name }}</a></td>
            <td>{{ $template->subject }}</td>
            <td>{{ collect($template->variables ?? [])->join(', ') }}</td>
            <td>{{ $template->is_active ? 'Active' : 'Inactive' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    {{ $templates->links() }}
  </section>
@endsection
