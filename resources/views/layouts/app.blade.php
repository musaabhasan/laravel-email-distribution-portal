<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
  <style>
    :root { color-scheme: light; --ink:#172033; --muted:#667085; --line:#d8dee8; --bg:#f5f7fb; --panel:#ffffff; --accent:#0f766e; --danger:#b42318; }
    * { box-sizing: border-box; }
    body { margin:0; font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background:var(--bg); color:var(--ink); }
    a { color:var(--accent); text-decoration:none; }
    a:hover { text-decoration:underline; }
    .shell { min-height:100vh; display:grid; grid-template-columns: 260px 1fr; }
    aside { background:#111827; color:#e5e7eb; padding:24px 18px; }
    aside h1 { font-size:1rem; line-height:1.35; margin:0 0 28px; }
    nav { display:grid; gap:6px; }
    nav a { color:#d1d5db; padding:10px 12px; border-radius:6px; }
    nav a:hover { background:#1f2937; text-decoration:none; }
    main { padding:28px; }
    .topline { display:flex; justify-content:space-between; align-items:center; gap:16px; margin-bottom:22px; }
    .topline h2 { margin:0; font-size:1.5rem; }
    .panel { background:var(--panel); border:1px solid var(--line); border-radius:8px; padding:20px; margin-bottom:18px; }
    .grid { display:grid; gap:16px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    .metric strong { display:block; font-size:2rem; margin-top:8px; }
    .metric span, th { color:var(--muted); font-size:.86rem; font-weight:700; text-transform:uppercase; letter-spacing:.03em; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:12px 10px; border-bottom:1px solid var(--line); text-align:left; vertical-align:top; }
    label { display:grid; gap:7px; font-weight:700; color:#344054; margin-bottom:14px; }
    input, select, textarea { width:100%; border:1px solid var(--line); border-radius:6px; padding:10px 12px; font:inherit; background:#fff; }
    textarea { min-height:160px; }
    .actions { display:flex; gap:10px; flex-wrap:wrap; align-items:center; }
    .button, button { display:inline-flex; align-items:center; justify-content:center; min-height:40px; padding:0 14px; border-radius:6px; border:1px solid var(--accent); background:var(--accent); color:#fff; font-weight:700; cursor:pointer; }
    .button.secondary { background:#fff; color:var(--accent); }
    .status { border-left:4px solid var(--accent); background:#ecfdf5; padding:12px 14px; margin-bottom:16px; }
    .badge { display:inline-flex; padding:4px 8px; border-radius:999px; background:#eef2ff; color:#3730a3; font-size:.78rem; font-weight:700; }
    .danger { color:var(--danger); }
    @media (max-width: 860px) { .shell { grid-template-columns:1fr; } aside { position:static; } }
  </style>
</head>
<body>
  <div class="shell">
    <aside>
      <h1>{{ config('app.name') }}</h1>
      <nav>
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('recipients.index') }}">Recipients</a>
        <a href="{{ route('groups.index') }}">Segments</a>
        <a href="{{ route('templates.index') }}">Templates</a>
        <a href="{{ route('broadcasts.index') }}">Broadcasts</a>
        <a href="{{ route('compliance.index') }}">Compliance</a>
      </nav>
      <form method="post" action="{{ route('logout') }}" style="margin-top:28px;">
        @csrf
        <button type="submit" style="width:100%; background:#374151; border-color:#374151;">Sign out</button>
      </form>
    </aside>
    <main>
      @if (session('status'))
        <div class="status">{{ session('status') }}</div>
      @endif
      @yield('content')
    </main>
  </div>
</body>
</html>
