<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Safe cleaner function
    function clean($conn, $value) {
        return isset($value) ? $conn->real_escape_string(trim($value)) : '';
    }

    // Basic fields
    $employee_number = clean($conn, $_POST['employee_number']);
    $name_with_initials = clean($conn, $_POST['name_with_initials']);
    $full_name = clean($conn, $_POST['full_name']);
    $registration_nc = clean($conn, $_POST['registration_nc']);
    $registration_mc = clean($conn, $_POST['registration_mc']);
    $nic = clean($conn, $_POST['nic']);
    $address = clean($conn, $_POST['address']);
    $contact = clean($conn, $_POST['contact']);
    $current_workplace = clean($conn, $_POST['current_workplace']);
    $current_workplace_enroll_date = clean($conn, $_POST['current_workplace_enroll_date'] ?? '');
    $civil_status = clean($conn, $_POST['civil_status']);
    $emergency_name = clean($conn, $_POST['emergency_name']);
    $emergency_relation = clean($conn, $_POST['emergency_relation']);
    $emergency_contact = clean($conn, $_POST['emergency_contact']);
    $additional_notes = clean($conn, $_POST['additional_notes']);

    // Qualifications
    $diploma_done = isset($_POST['diploma_done']) ? 1 : 0;
    $diploma_school = clean($conn, $_POST['diploma_school']);
    $diploma_year = clean($conn, $_POST['diploma_year']);

    $bsc_done = isset($_POST['bsc_done']) ? 1 : 0;
    $bsc_school = clean($conn, $_POST['bsc_school']);
    $bsc_year = clean($conn, $_POST['bsc_year']);

    $msc_done = isset($_POST['msc_done']) ? 1 : 0;
    $msc_school = clean($conn, $_POST['msc_school']);
    $msc_year = clean($conn, $_POST['msc_year']);

    // Experience
    $experience_data = "";
    $exp_institution = $_POST['exp_institution'] ?? [];
    $exp_section = $_POST['exp_section'] ?? [];
    $exp_duration = $_POST['exp_duration'] ?? [];

    for ($i = 0; $i < count($exp_institution); $i++) {
        $institution = clean($conn, $exp_institution[$i] ?? '');
        $section = clean($conn, $exp_section[$i] ?? '');
        $duration = clean($conn, $exp_duration[$i] ?? '');

        if ($institution || $section || $duration) {
            $experience_data .= "Institution: $institution, Section: $section, Duration: $duration;\n";
        }
    }

    // Image upload
    $folder = '';
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        $temp = $_FILES['image']['tmp_name'];
        $folder = "uploads/" . $image;
        move_uploaded_file($temp, $folder);
    }

    // Grades and Enrollment Dates
    $grades_array = $_POST['grades'] ?? [];
    $grade_string = implode(",", $grades_array);

    $enroll_date_spg = clean($conn, $_POST['enroll_date_spg'] ?? '');
    $enroll_date_supra = clean($conn, $_POST['enroll_date_supra'] ?? '');
    $enroll_date_1 = clean($conn, $_POST['enroll_date_1'] ?? '');
    $enroll_date_2 = clean($conn, $_POST['enroll_date_2'] ?? '');
    $enroll_date_3 = clean($conn, $_POST['enroll_date_3'] ?? '');

    // SQL Insert
    $sql = "INSERT INTO nurses (
        employee_number, name_with_initials, full_name, registration_nc, registration_mc, nic,
        address, contact, image, current_workplace, current_workplace_enroll_date,
        civil_status, diploma_done, diploma_school, diploma_year,
        bsc_done, bsc_school, bsc_year,
        msc_done, msc_school, msc_year,
        exp_institution, emergency_name, emergency_relation, emergency_contact, additional_notes,
        grade, enroll_date_spg, enroll_date_supra, enroll_date_1, enroll_date_2, enroll_date_3
    ) VALUES (
        '$employee_number', '$name_with_initials', '$full_name', '$registration_nc', '$registration_mc', '$nic',
        '$address', '$contact', '$folder', '$current_workplace', '$current_workplace_enroll_date',
        '$civil_status', '$diploma_done', '$diploma_school', '$diploma_year',
        '$bsc_done', '$bsc_school', '$bsc_year',
        '$msc_done', '$msc_school', '$msc_year',
        '$experience_data', '$emergency_name', '$emergency_relation', '$emergency_contact', '$additional_notes',
        '$grade_string',
        " . ($enroll_date_spg ? "'$enroll_date_spg'" : "NULL") . ",
        " . ($enroll_date_supra ? "'$enroll_date_supra'" : "NULL") . ",
        " . ($enroll_date_1 ? "'$enroll_date_1'" : "NULL") . ",
        " . ($enroll_date_2 ? "'$enroll_date_2'" : "NULL") . ",
        " . ($enroll_date_3 ? "'$enroll_date_3'" : "NULL") . "
    )";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Nurse profile added successfully.";
    } else {
        echo "❌ MySQL Error: " . $conn->error;
        echo "<br><pre>$sql</pre>";
    }
}
?>
