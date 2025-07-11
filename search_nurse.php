<?php
session_start();
if (!isset($_SESSION['matron'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Search Nurse</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .section {
      background-color: #f2f2f2;
      padding: 20px 25px;
      margin-top: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(138, 0, 79, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    @media (max-width: 768px) {
  body {
    padding: 15px;
  }
  .section {
    padding: 15px;
  }
  img.profile-img {
    width: 120px;
    float: none;
    margin: 0 auto 20px;
    display: block;
    box-shadow: 0 4px 10px rgba(138,0,79,0.3);
  }
  form {
    flex-direction: column;
    align-items: stretch;
  }
  label, input[type="text"], input[type="submit"] {
    width: 100%;
    margin: 5px 0;
  }
  input[type="submit"] {
    margin-top: 10px;
  }
  .back-button {
    font-size: 1rem;
    padding: 8px 12px;
    display: block;
    text-align: center;
    margin-bottom: 20px;
  }
}

    .section:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(138, 0, 79, 0.25);
    }
    img.profile-img {
      border: 3px solid #8A004F;
      border-radius: 12px;
      float: right;
      margin-left: 20px;
      margin-top: -10px;
      width: 160px;
      height: auto;
      box-shadow: 0 4px 12px rgba(138, 0, 79, 0.4);
      transition: transform 0.3s ease;
      cursor: zoom-in;
    }
    img.profile-img:hover {
      transform: scale(1.05);
    }
    .back-button {
      display: inline-block;
      margin-bottom: 15px;
      text-decoration: none;
      color: #8A004F;
      font-weight: 700;
      font-size: 1.1rem;
      padding: 6px 12px;
      border: 2px solid #8A004F;
      border-radius: 8px;
      transition: background-color 0.3s ease, color 0.3s ease;
      user-select: none;
    }
    .back-button:hover {
      background-color: #8A004F;
      color: white;
      box-shadow: 0 4px 14px rgba(138, 0, 79, 0.6);
    }
    h2 {
      color: #8A004F;
      font-weight: 700;
      font-size: 2.4rem;
      text-align: center;
      margin-bottom: 15px;
      text-shadow: 1px 1px 5px rgba(138, 0, 79, 0.4);
      user-select: none;
    }
    form {
      margin-bottom: 25px;
      text-align: center;
    }
    label {
      font-weight: 600;
      font-size: 1.15rem;
      margin-right: 10px;
      user-select: none;
    }
    input[type="text"] {
      padding: 10px 14px;
      width: 280px;
      border: 2px solid #8A004F;
      border-radius: 12px;
      font-size: 1rem;
      outline: none;
      transition: border-color 0.3s ease;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
    }
    input[type="text"]:focus {
      border-color: #a0005d;
      box-shadow: 0 0 8px #a0005d;
    }
    input[type="submit"] {
      background-color: #8A004F;
      color: white;
      padding: 10px 22px;
      border: none;
      border-radius: 12px;
      font-size: 1.15rem;
      font-weight: 700;
      cursor: pointer;
      margin-left: 12px;
      transition: background-color 0.3s ease, box-shadow 0.3s ease;
      user-select: none;
    }
    input[type="submit"]:hover {
      background-color: #a0005d;
      box-shadow: 0 6px 15px rgba(160, 0, 93, 0.6);
    }
    p {
      font-size: 1.1rem;
      line-height: 1.5;
      color: #4a0044;
      user-select: text;
    }
    pre {
      background: #f9f0f4;
      border-left: 6px solid #8A004F;
      padding: 12px 18px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 1rem;
      white-space: pre-wrap;
      border-radius: 10px;
      color: #4a0044;
      user-select: text;
    }
    a {
      display: inline-block;
      margin-top: 20px;
      color: #8A004F;
      font-weight: 700;
      text-decoration: none;
      font-size: 1.1rem;
      transition: color 0.3s ease;
      user-select: none;
    }
    a:hover {
      color: #a0005d;
      text-decoration: underline;
      cursor: pointer;
    }
    /* Error message */
    .error-msg {
      color: #c4003d;
      font-weight: 700;
      font-size: 1.2rem;
      text-align: center;
      margin-top: 25px;
      user-select: none;
    }
  </style>
</head>
<body>

<a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>

<h2>Search Nurse Profile</h2>
<form method="GET" action="">
  <label for="search_term">Employee Number or NIC:</label>
  <input type="text" name="search_term" id="search_term" required autocomplete="off" placeholder="Enter Employee Number or NIC">
  <input type="submit" value="Search">
</form>

<?php
if (isset($_GET['search_term'])) {
    include 'includes/db.php';
    $term = $conn->real_escape_string($_GET['search_term']);

    $sql = "SELECT * FROM nurses WHERE employee_number='$term' OR nic='$term'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (!empty($row['image'])) {
            echo "<img src='{$row['image']}' class='profile-img' alt='Nurse Photo'>";
        }

        echo "<div class='section'><h3>🧾 Basic Information</h3>";
        echo "<p><strong>Full Name:</strong> {$row['full_name']}</p>";
        echo "<p><strong>Name with Initials:</strong> {$row['name_with_initials']}</p>";
        echo "<p><strong>Employee Number:</strong> {$row['employee_number']}</p>";
        echo "<p><strong>NIC:</strong> {$row['nic']}</p>";
        echo "<p><strong>Registration No (NC):</strong> {$row['registration_nc']}</p>";
        echo "<p><strong>Registration No (MC):</strong> {$row['registration_mc']}</p>";
        echo "<p><strong>Contact:</strong> {$row['contact']}</p>";
        echo "<p><strong>Address:</strong> {$row['address']}</p>";
        echo "<p><strong>Civil Status:</strong> {$row['civil_status']}</p>";
        echo "<p><strong>Current Workplace:</strong> {$row['current_workplace']}</p>";
        echo "<p><strong>Workplace Enroll Date:</strong> {$row['current_workplace_enroll_date']}</p>";
        echo "</div>";

        echo "<div class='section'><h3>🎓 Professional Qualifications</h3>";
        echo "<p><strong>Diploma:</strong> " . ($row['diploma_done'] ? "Yes" : "No") . "</p>";
        echo "<p>School: {$row['diploma_school']}, Year: {$row['diploma_year']}</p>";

        echo "<p><strong>BSC:</strong> " . ($row['bsc_done'] ? "Yes" : "No") . "</p>";
        echo "<p>School: {$row['bsc_school']}, Year: {$row['bsc_year']}</p>";

        echo "<p><strong>MSC:</strong> " . ($row['msc_done'] ? "Yes" : "No") . "</p>";
        echo "<p>School: {$row['msc_school']}, Year: {$row['msc_year']}</p>";
        echo "</div>";

        echo "<div class='section'><h3>🩺 Experience</h3>";
        echo "<pre>" . nl2br(htmlspecialchars($row['exp_institution'])) . "</pre>";
        echo "</div>";

        echo "<div class='section'><h3>🚨 Emergency Contact</h3>";
        echo "<p><strong>Name:</strong> {$row['emergency_name']}</p>";
        echo "<p><strong>Relation:</strong> {$row['emergency_relation']}</p>";
        echo "<p><strong>Contact:</strong> {$row['emergency_contact']}</p>";
        echo "</div>";

        echo "<div class='section'><h3>🗂️ Grades & Enrollment Dates</h3>";
        $grades = explode(',', $row['grade']);
        echo "<p><strong>Assigned Grades:</strong> " . implode(", ", $grades) . "</p>";
        if (!empty($row['enroll_date_spg'])) echo "<p><strong>SPG Enroll Date:</strong> {$row['enroll_date_spg']}</p>";
        if (!empty($row['enroll_date_supra'])) echo "<p><strong>Supra Enroll Date:</strong> {$row['enroll_date_supra']}</p>";
        if (!empty($row['enroll_date_1'])) echo "<p><strong>Grade 1 Enroll Date:</strong> {$row['enroll_date_1']}</p>";
        if (!empty($row['enroll_date_2'])) echo "<p><strong>Grade 2 Enroll Date:</strong> {$row['enroll_date_2']}</p>";
        if (!empty($row['enroll_date_3'])) echo "<p><strong>Grade 3 Enroll Date:</strong> {$row['enroll_date_3']}</p>";
        echo "</div>";

        echo "<div class='section'><h3>📝 Additional Notes</h3>";
        echo "<p>" . nl2br(htmlspecialchars($row['additional_notes'])) . "</p>";
        echo "</div>";

        echo "<br><a href='edit_nurse.php?employee_number={$row['employee_number']}'>✏️ Edit This Nurse</a>";
    } else {
        echo "<p class='error-msg'>❌ No nurse found with Employee Number or NIC: <strong>" . htmlspecialchars($term) . "</strong></p>";
    }
}
?>
</body>
</html>
