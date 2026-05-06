<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verify MFA</title>
  <style>
    body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:system-ui, sans-serif; background:#f5f7fb; color:#172033; }
    form { width:min(420px, calc(100vw - 32px)); background:#fff; border:1px solid #d8dee8; border-radius:8px; padding:28px; }
    label { display:grid; gap:8px; margin-bottom:14px; font-weight:700; }
    input { border:1px solid #d8dee8; border-radius:6px; padding:11px; font:inherit; letter-spacing:.12em; text-align:center; }
    button { width:100%; min-height:42px; border:0; border-radius:6px; background:#0f766e; color:#fff; font-weight:700; }
    .error { color:#b42318; }
  </style>
</head>
<body>
  <form method="post" action="{{ route('mfa.verify') }}">
    @csrf
    <h1>Multi-factor verification</h1>
    @if ($errors->any()) <p class="error">{{ $errors->first() }}</p> @endif
    <label>Six-digit code <input name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" required autofocus></label>
    <button type="submit">Verify</button>
  </form>
</body>
</html>
