<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Club Nexus</title>
  <!-- Import Fredoka font from Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Fredoka&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/c-icon" href="/reg-user/src/clubnexusicon.ico">
  <style>
    html, body {
      height: 100%;
      margin: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      font-family: 'Fredoka', cursive, Arial, sans-serif;
      color: #2d85db;
    }
    .loader {
      width: 80px;
      height: 80px;
      border: 8px solid #ffde59;
      border-top: 8px solid #2d85db;
      border-right: 8px solid #0075a3;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      position: relative;
      margin-bottom: 20px;
    }
    .loader::after {
      content: '';
      position: absolute;
      top: 10px;
      left: 10px;
      right: 10px;
      bottom: 10px;
      border: 4px solid rgba(255, 222, 89, 0.2);
      border-radius: 50%;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .loading-text {
      font-size: 1.5rem;
      font-weight: 600;
      letter-spacing: 0.05em;
      white-space: nowrap;
    }
    .dots::after {
      content: '';
      display: inline-block;
      width: 1ch;
      animation: dots 1.5s steps(3, end) infinite;
    }
    @keyframes dots {
      0%, 20% {
        content: '';
      }
      40% {
        content: '.';
      }
      60% {
        content: '..';
      }
      80%, 100% {
        content: '...';
      }
    }
  </style>

</head>
<body>
  <div class="loader" aria-label="Loading"></div>
  <div class="loading-text">Loading<span class="dots"></span></div>

  <script>
    // After 3 seconds, redirect to login page
    setTimeout(() => {
      window.location.href = 'reg-user/login_form.php';
    }, 2050);
  </script>
</body>
</html>
