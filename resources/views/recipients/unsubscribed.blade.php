<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Unsubscribed</title>
  <style>
    body { margin:0; min-height:100vh; display:grid; place-items:center; font-family:system-ui, sans-serif; background:#f5f7fb; color:#172033; }
    main { max-width:560px; background:#fff; border:1px solid #d8dee8; border-radius:8px; padding:28px; }
  </style>
</head>
<body>
  <main>
    <h1>Subscription updated</h1>
    <p>{{ $recipient->email }} has been removed from future distributions.</p>
  </main>
</body>
</html>
