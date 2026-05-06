<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sign in</title>
  <style>
    body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:system-ui, sans-serif; background:#f5f7fb; color:#172033; }
    form { width:min(420px, calc(100vw - 32px)); background:#fff; border:1px solid #d8dee8; border-radius:8px; padding:28px; }
    label { display:grid; gap:8px; margin-bottom:14px; font-weight:700; }
    input { border:1px solid #d8dee8; border-radius:6px; padding:11px; font:inherit; }
    button { width:100%; min-height:42px; border:0; border-radius:6px; background:#0f766e; color:#fff; font-weight:700; }
    .error { color:#b42318; }
  </style>
</head>
<body>
  <form method="post" action="{{ route('login.store') }}">
    @csrf
    <h1>Sign in</h1>
    @if ($errors->any()) <p class="error">{{ $errors->first() }}</p> @endif
    <label>Email <input name="email" type="email" required autofocus value="{{ old('email') }}"></label>
    <label>Password <input name="password" type="password" required></label>
    <label><span><input name="remember" type="checkbox" value="1"> Remember this browser</span></label>
    <button type="submit">Continue</button>
  </form>
</body>
</html>
