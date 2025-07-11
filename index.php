<?php
session_start();
if (isset($_SESSION['matron'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Welcome to Nurse Manager</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f6, #fce4ec);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      animation: fadeInBg 1s ease-in-out;
    }

    .welcome-box {
      background-color: #ffffff;
      padding: 50px 40px;
      border-radius: 16px;
      box-shadow: 0 12px 35px rgba(138, 0, 79, 0.2);
      text-align: center;
      max-width: 400px;
      width: 90%;
      animation: fadeIn 1.2s ease;
    }

    h1 {
      color: #8A004F;
      font-size: 30px;
      margin-bottom: 20px;
    }

    p {
      font-size: 16px;
      color: #555;
      margin-bottom: 30px;
    }

    .start-btn {
      background-color: #8A004F;
      color: #fff;
      padding: 14px 30px;
      font-size: 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s ease, transform 0.3s ease;
      display: inline-block;
    }

    .start-btn:hover {
      background-color: #a0005d;
      transform: scale(1.05);
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInBg {
      from { background: #ffffff; }
      to { background: linear-gradient(to right, #fff0f6, #fce4ec); }
    }

    @media screen and (max-width: 480px) {
      .welcome-box {
        padding: 30px 20px;
      }

      h1 {
        font-size: 24px;
      }

      .start-btn {
        padding: 12px 24px;
        font-size: 15px;
      }
    }
  </style>
</head>
<body>

  <div class="welcome-box">
    <h1>Welcome to Nurse Manager 🏥</h1>
    <p>Secure matron portal for managing nurse profiles, qualifications, and experiences.</p>
    <a href="login.php" class="start-btn">👩‍⚕️ Login as Matron</a>
  </div>

</body>
</html>
