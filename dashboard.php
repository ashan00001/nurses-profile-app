<?php
session_start();
if (!isset($_SESSION['matron'])) {
    header("Location: login.html");
    exit();
}
include 'includes/db.php';

// Fetch total nurses count
$totalNursesQuery = $conn->query("SELECT COUNT(*) as total FROM nurses");
$totalNurses = $totalNursesQuery->fetch_assoc()['total'];

// Fetch nurse list
$nursesQuery = $conn->query("SELECT employee_number, name_with_initials, full_name, image FROM nurses ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Matron Dashboard</title>
  <link rel="stylesheet" href="css/style.css" />
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff0f6, #fce4ec);
      color: #4b0033;
    }

    h2.welcome {
      margin: 40px 0 5px;
      text-align: center;
      font-size: 2rem;
      color: #8A004F;
    }

    h3.subheading {
      margin: 0 0 30px;
      font-size: 1rem;
      font-weight: 500;
      color: #b3395a;
      text-align: center;
      font-style: italic;
    }

    .main-container {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      padding: 20px;
      justify-content: center;
    }

    .left-panel, .right-panel {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
      padding: 25px;
      flex: 1 1 400px;
      max-width: 500px;
    }

    .right-panel h3 {
      color: #8A004F;
      margin-bottom: 10px;
    }

    .nurse-list ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .nurse-list li {
      border-bottom: 1px solid #eee;
      padding: 12px 8px;
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .nurse-list img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ddd;
    }

    .nurse-info {
      display: flex;
      align-items: center;
      gap: 12px;
      flex: 1;
      flex-wrap: wrap;
    }

    .nurse-details {
      display: flex;
      flex-direction: column;
    }

    .nurse-details .name {
      font-weight: bold;
      color: #8A004F;
    }

    .nurse-details .emp-id {
      font-size: 13px;
      color: #777;
    }

    .delete-form {
      margin: 0;
    }

    .delete-btn {
      background: #c4003d;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 8px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .delete-btn:hover {
      background: #a00033;
    }

    .left-panel {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 25px;
    }

    .dashboard-links {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 15px;
    }

    .dashboard-links a {
      background: #8A004F;
      color: white;
      padding: 14px 22px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .dashboard-links a:hover {
      background: #a0005d;
    }

    .stats {
      background: #8A004F;
      color: #fff;
      padding: 15px 25px;
      border-radius: 20px;
      font-size: 1.1rem;
      font-weight: 600;
      box-shadow: 0 4px 20px rgba(138, 0, 79, 0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
    }

    .stats svg {
      width: 24px;
      height: 24px;
      fill: #fff;
    }

    @media (max-width: 500px) {
      .nurse-info {
        flex-direction: column;
        align-items: flex-start;
      }

      .delete-btn {
        margin-top: 10px;
        width: 100%;
      }
    }
  </style>
</head>
<body>

  <h2 class="welcome">Welcome, Rohana Amarasinghe</h2>
  <h3 class="subheading">Matron, General Hospital Kandy</h3>

  <div class="main-container">
    <!-- LEFT PANEL (Was Right Panel) -->
    <div class="left-panel">
      <div class="dashboard-links">
        <a href="add_nurse.php">➕ Add Nurse</a>
        <a href="search_nurse.php">🔍 Search Nurse</a>
        <a href="logout.php">🚪 Logout</a>
      </div>

      <div class="stats">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
        </svg>
        Total Nurses in System: <?= $totalNurses ?>
      </div>
    </div>

    <!-- RIGHT PANEL (Was Left Panel) -->
    <div class="right-panel">
      <h3>👩‍⚕️ Recently Added Nurses</h3>
      <div class="nurse-list">
        <ul>
          <?php
          $nursesQuery->data_seek(0);
          while ($nurse = $nursesQuery->fetch_assoc()):
              $image = !empty($nurse['image']) ? htmlspecialchars($nurse['image']) : 'default.jpg';
          ?>
            <li>
              <div class="nurse-info">
                <img src="<?= $image ?>" alt="Photo" />
                <div class="nurse-details">
                  <span class="name"><?= htmlspecialchars($nurse['name_with_initials']) ?></span>
                  <span><?= htmlspecialchars($nurse['full_name']) ?></span>
                  <span class="emp-id">ID: <?= htmlspecialchars($nurse['employee_number']) ?></span>
                </div>
              </div>
              <form action="delete_nurses.php" method="POST" class="delete-form" onsubmit="return confirm('Delete this nurse?');">
                <input type="hidden" name="delete_ids[]" value="<?= htmlspecialchars($nurse['employee_number']) ?>">
                <button type="submit" class="delete-btn">🗑</button>
              </form>
            </li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>
  </div>

</body>
</html>
