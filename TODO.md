# TODO: Fix Dashboard Access Before Login

- [x] Move '/dashboard' route out of auth middleware group in routes/web.php
- [x] Change root route view from 'dashboard' to 'user/dashboard' in routes/web.php
- [x] Test access to '/' and '/dashboard' without login to ensure landing page shows (server running on http://127.0.0.1:8000)
