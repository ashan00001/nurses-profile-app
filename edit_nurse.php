<?php
session_start();
if (!isset($_SESSION['matron'])) {
    header("Location: login.html");
    exit();
}
include 'includes/db.php';

$emp = $_GET['employee_number'];
$result = $conn->query("SELECT * FROM nurses WHERE employee_number='$emp'");
if ($result->num_rows === 0) {
    echo "Nurse not found.";
    exit();
}
$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Nurse</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      padding: 20px;
      margin: 0;
    }
    h2, h3 {
      text-align: center;
      color: #8A004F;
    }
    .back-button {
      text-decoration: none;
      color: #8A004F;
      font-weight: bold;
      margin-bottom: 20px;
      display: inline-block;
    }
    form {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="file"],
    input[type="date"],
    select,
    textarea {
      width: 100%;
      max-width: 300px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }
    textarea {
      resize: vertical;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
    }
    td input {
      width: 100%;
    }
    button {
      background-color: #8A004F;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    button:hover {
      background-color: #6c003a;
    }
    .submit-btn {
      display: block;
      margin: 30px auto;
      background-color: #8A004F;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
    }
    .section {
      margin-bottom: 30px;
      background: #f7f7f7;
      padding: 20px;
      border-radius: 10px;
    }
    img.profile-pic {
      display: block;
      margin: 0 auto 20px;
      max-width: 150px;
      border: 3px solid #ccc;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>
<h2>📝 Edit Nurse Profile</h2>

<?php if (!empty($row['image'])): ?>
  <img src="<?= $row['image'] ?>" alt="Profile" class="profile-pic">
<?php endif; ?>

<form action="update_nurse.php" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?= $row['id'] ?>">

  <div class="section">
    <h3>Basic Info</h3>
    <label>Name with Initials</label>
    <input type="text" name="name_with_initials" value="<?= $row['name_with_initials'] ?>">

    <label>Full Name</label>
    <input type="text" name="full_name" value="<?= $row['full_name'] ?>">

    <label>Employee Number (not editable)</label>
    <input type="text" value="<?= $row['employee_number'] ?>" readonly>

    <label>NIC</label>
    <input type="text" name="nic" value="<?= $row['nic'] ?>">

    <label>Registration No (NC)</label>
    <input type="text" name="registration_nc" value="<?= $row['registration_nc'] ?>">

    <label>Registration No (MC)</label>
    <input type="text" name="registration_mc" value="<?= $row['registration_mc'] ?>">

    <label>Contact</label>
    <input type="text" name="contact" value="<?= $row['contact'] ?>">

    <label>Address</label>
    <input type="text" name="address" value="<?= $row['address'] ?>">

    <label>Current Workplace</label>
    <input type="text" name="current_workplace" value="<?= $row['current_workplace'] ?>">

    <label>Workplace Enrollment Date</label>
    <input type="date" name="current_workplace_enroll_date" value="<?= $row['current_workplace_enroll_date'] ?>">
  </div>

  <div class="section">
    <h3>Grades and Enrollment Dates</h3>
    <?php
    $grades = ["spg" => "SPG", "supra" => "Supra", "1" => "Grade 1", "2" => "Grade 2", "3" => "Grade 3"];
    foreach ($grades as $key => $label) {
      $enrollDate = $row["enroll_date_$key"] ?? '';
      echo "<label><input type='checkbox' name='grades[]' value='$label' " . ($enrollDate ? "checked" : "") . "> $label</label>";
      echo "<input type='date' name='enroll_date_$key' value='$enrollDate'><br>";
    }
    ?>
  </div>

  <div class="section">
    <h3>Professional Qualifications</h3>
    <?php
    $quals = ["diploma" => "Diploma", "bsc" => "BSC", "msc" => "MSC"];
    foreach ($quals as $key => $label) {
      echo "<label><input type='checkbox' name='{$key}_done' value='1' ". ($row[$key.'_done'] ? 'checked' : '') ."> $label</label>";
      echo "<input type='text' name='{$key}_school' placeholder='School' value='{$row[$key.'_school']}'>";
      echo "<input type='text' name='{$key}_year' placeholder='Year' value='{$row[$key.'_year']}'>";
    }
    ?>
  </div>

  <div class="section">
    <h3>Experience</h3>
    <table id="experience-table">
      <thead><tr><th>Institution</th><th>Section</th><th>Duration</th><th>Action</th></tr></thead>
      <tbody>
        <?php
        $experiences = explode(";\n", trim($row['exp_institution']));
        foreach ($experiences as $exp) {
          if (trim($exp) === '') continue;
          preg_match('/Institution: (.*), Section: (.*), Duration: (.*)/', $exp, $matches);
          echo "<tr>
              <td><input type='text' name='exp_institution[]' value='".($matches[1] ?? '')."'></td>
              <td><input type='text' name='exp_section[]' value='".($matches[2] ?? '')."'></td>
              <td><input type='text' name='exp_duration[]' value='".($matches[3] ?? '')."'></td>
              <td><button type='button' onclick='removeRow(this)'>Remove</button></td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
    <br><button type="button" onclick="addExperienceRow()">➕ Add Experience</button>
  </div>

  <div class="section">
    <h3>Civil Status</h3>
    <select name="civil_status">
      <option value="Married" <?= $row['civil_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
      <option value="Single" <?= $row['civil_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
    </select>
  </div>

  <div class="section">
    <h3>Emergency Contact</h3>
    <label>Name</label>
    <input type="text" name="emergency_name" value="<?= $row['emergency_name'] ?>">
    <label>To Spoke</label>
    <input type="text" name="emergency_to_spoke" value="<?= $row['emergency_to_spoke'] ?>">
    <label>Relation</label>
    <input type="text" name="emergency_relation" value="<?= $row['emergency_relation'] ?>">
    <label>Contact</label>
    <input type="text" name="emergency_contact" value="<?= $row['emergency_contact'] ?>">
  </div>

  <div class="section">
    <h3>Additional Notes</h3>
    <textarea name="additional_notes" rows="4"><?= $row['additional_notes'] ?></textarea>
  </div>

  <div class="section">
    <h3>Update Profile Image</h3>
    <label>Upload New Image (optional)</label>
    <input type="file" name="image" accept="image/*">
  </div>

  <input type="submit" value="Update Nurse" class="submit-btn">
</form>

<script>
function addExperienceRow() {
  const table = document.getElementById('experience-table').getElementsByTagName('tbody')[0];
  const row = table.insertRow();
  row.innerHTML = `
    <td><input type="text" name="exp_institution[]"></td>
    <td><input type="text" name="exp_section[]"></td>
    <td><input type="text" name="exp_duration[]"></td>
    <td><button type="button" onclick="removeRow(this)">Remove</button></td>
  `;
}
function removeRow(btn) {
  btn.parentElement.parentElement.remove();
}
</script>

</body>
</html>
