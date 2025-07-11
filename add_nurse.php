<?php
session_start();
if (!isset($_SESSION['matron'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Nurse</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f7f7f7, #fff);
      padding: 20px;
      margin: 0;
    }
    h2, h3 {
      color: #8A004F;
      text-align: center;
    }
    .form-section {
      display: flex;
      flex-wrap: wrap;
      gap: 40px;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-weight: bold;
      margin: 10px 0 5px;
    }
    input[type="text"],
    input[type="file"],
    input[type="date"],
    select,
    textarea {
      width: 100%;
      max-width: 280px;
      padding: 10px;
      border-radius: 6px;
      border: 1px solid #ccc;
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
      text-align: left;
    }
    td input {
      width: 100%;
    }
    button {
      background-color: #8A004F;
      color: white;
      padding: 8px 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    button:hover {
      background-color: #6c003c;
    }
    .back-button {
      display: inline-block;
      margin-bottom: 15px;
      text-decoration: none;
      color: #8A004F;
      font-weight: bold;
    }
    .submit-btn {
      margin-top: 20px;
      display: block;
      background-color: #8A004F;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .submit-btn:hover {
      background-color: #70003f;
    }
    @media screen and (max-width: 768px) {
      .form-section {
        flex-direction: column;
      }
      td[data-label]::before {
        content: attr(data-label) ": ";
        font-weight: bold;
        display: block;
      }
      table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
      }
      thead {
        display: none;
      }
      tr {
        margin-bottom: 10px;
        border: 1px solid #ccc;
        padding: 10px;
      }
    }
  </style>
</head>
<body>
<a href="dashboard.php" class="back-button">⬅ Back to Dashboard</a>
<h2>➕ Add Nurse Profile</h2>

<form action="save_nurse.php" method="POST" enctype="multipart/form-data">
  <div class="form-section">
    <div>
      <label>Name with Initials</label>
      <input type="text" name="name_with_initials" required>
      <label>Full Name</label>
      <input type="text" name="full_name" required>
      <label>Employee Number</label>
      <input type="text" name="employee_number" required>
      <label>Registration Number (NC)</label>
      <input type="text" name="registration_nc">
      <label>Registration Number (MC)</label>
      <input type="text" name="registration_mc">
      <label>NIC</label>
      <input type="text" name="nic" required>
      <label>Address</label>
      <input type="text" name="address">
      <label>Contact Number</label>
      <input type="text" name="contact">
    </div>
    <div>
      <label>Current Workplace and Ward Number</label>
      <input type="text" name="current_workplace">

      <!-- ✅ Inserted missing field -->
      <label>Workplace Enrollment Date</label>
      <input type="date" name="current_workplace_enroll_date" required>

      <label>Grades and Enrollment Dates</label>
      <div style="display: flex; flex-wrap: wrap; gap: 20px; max-width: 600px;">
        <?php
        $grades = ["spg" => "SPG", "supra" => "Supra", "1" => "Grade 1", "2" => "Grade 2", "3" => "Grade 3"];
        foreach ($grades as $id => $label) {
          echo "<div style='flex: 1 1 120px;'>";
          echo "<input type='checkbox' id='grade_$id' name='grades[]' value='$label' onchange='toggleDateInput(this)'>";
          echo "<label for='grade_$id'>$label</label><br>";
          echo "<input type='date' name='enroll_date_$id' id='enroll_$id' disabled style='margin-top: 5px; width: 100%;'>";
          echo "</div>";
        }
        ?>
      </div>
      <label>Upload Image</label>
      <input type="file" name="image" accept="image/*" required>
    </div>
  </div>

  <h3>🎓 Professional Qualifications</h3>
  <table>
    <thead>
      <tr><th>Qualification</th><th>Done</th><th>University</th><th>Year</th></tr>
    </thead>
    <tbody>
      <?php
      $quals = ["diploma" => "Diploma", "bsc" => "BSC", "msc" => "MSC"];
      foreach ($quals as $key => $label) {
        echo "<tr>";
        echo "<td>$label</td>";
        echo "<td><input type='checkbox' name='{$key}_done' value='1'></td>";
        echo "<td><input type='text' name='{$key}_school'></td>";
        echo "<td><input type='text' name='{$key}_year'></td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

  <h3>🦥 Experience</h3>
  <table id="experience-table">
    <thead><tr><th>Institution</th><th>Section</th><th>Duration</th><th>Action</th></tr></thead>
    <tbody>
      <tr>
        <td><input type="text" name="exp_institution[]"></td>
        <td><input type="text" name="exp_section[]"></td>
        <td><input type="text" name="exp_duration[]"></td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
      </tr>
    </tbody>
  </table>
  <button type="button" onclick="addExperienceRow()">➕ Add Experience</button>

  <h3>💼 Civil Status</h3>
  <select name="civil_status">
    <option value="Married">Married</option>
    <option value="Single">Single</option>
  </select>

  <h3>🚨 Emergency Contact</h3>
  <label>Name</label>
  <input type="text" name="emergency_name">
  <label>Relation</label>
  <input type="text" name="emergency_relation">
  <label>Contact</label>
  <input type="text" name="emergency_contact">

  <label>📝 Additional Notes</label>
  <textarea name="additional_notes" rows="4" cols="50"></textarea>
  <input type="submit" value="Submit Profile" class="submit-btn">
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
function toggleDateInput(checkbox) {
  const id = checkbox.id.replace('grade_', '');
  const dateInput = document.getElementById('enroll_' + id);
  dateInput.disabled = !checkbox.checked;
  if (!checkbox.checked) dateInput.value = '';
}
</script>
</body>
</html>
