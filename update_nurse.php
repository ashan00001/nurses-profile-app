<?php
include 'includes/db.php';

$id = $_POST['id'];
$name_with_initials = mysqli_real_escape_string($conn, $_POST['name_with_initials']);
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$nic = mysqli_real_escape_string($conn, $_POST['nic']);
$registration_nc = mysqli_real_escape_string($conn, $_POST['registration_nc']);
$registration_mc = mysqli_real_escape_string($conn, $_POST['registration_mc']);
$grade = mysqli_real_escape_string($conn, $_POST['grade']);
$contact = mysqli_real_escape_string($conn, $_POST['contact']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);
$current_workplace = mysqli_real_escape_string($conn, $_POST['current_workplace']);
$current_workplace_enroll_date = mysqli_real_escape_string($conn, $_POST['current_workplace_enroll_date']);
$additional_notes = mysqli_real_escape_string($conn, $_POST['additional_notes']);

// Emergency
$emergency_name = mysqli_real_escape_string($conn, $_POST['emergency_name']);
$emergency_relation = mysqli_real_escape_string($conn, $_POST['emergency_relation']);
$emergency_contact = mysqli_real_escape_string($conn, $_POST['emergency_contact']);
$emergency_to_spoke = isset($_POST['emergency_to_spoke']) ? mysqli_real_escape_string($conn, $_POST['emergency_to_spoke']) : "";

// Qualifications
$diploma_done = isset($_POST['diploma_done']) ? 1 : 0;
$bsc_done = isset($_POST['bsc_done']) ? 1 : 0;
$msc_done = isset($_POST['msc_done']) ? 1 : 0;

$diploma_school = mysqli_real_escape_string($conn, $_POST['diploma_school']);
$diploma_year = mysqli_real_escape_string($conn, $_POST['diploma_year']);
$bsc_school = mysqli_real_escape_string($conn, $_POST['bsc_school']);
$bsc_year = mysqli_real_escape_string($conn, $_POST['bsc_year']);
$msc_school = mysqli_real_escape_string($conn, $_POST['msc_school']);
$msc_year = mysqli_real_escape_string($conn, $_POST['msc_year']);

// Grades enrollment dates
$grades = $_POST['grades'] ?? [];
$enroll_dates = [];
$grade_keys = ['spg', 'supra', '1', '2', '3'];
foreach ($grade_keys as $key) {
    $enroll_dates["enroll_date_$key"] = mysqli_real_escape_string($conn, $_POST["enroll_date_$key"] ?? '');
}

// Experience
$exp_data = "";
if (isset($_POST['exp_institution'])) {
    for ($i = 0; $i < count($_POST['exp_institution']); $i++) {
        $inst = mysqli_real_escape_string($conn, $_POST['exp_institution'][$i]);
        $sect = mysqli_real_escape_string($conn, $_POST['exp_section'][$i]);
        $dur = mysqli_real_escape_string($conn, $_POST['exp_duration'][$i]);
        if ($inst || $sect || $dur) {
            $exp_data .= "Institution: $inst, Section: $sect, Duration: $dur;\n";
        }
    }
}

// Image Upload
$image_path = '';
if (!empty($_FILES['image']['name'])) {
    $target_dir = "uploads/";
    $file_name = time() . "_" . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $file_name;
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    $image_path = $target_file;
}

// Build SQL
$sql = "UPDATE nurses SET 
    name_with_initials='$name_with_initials',
    full_name='$full_name',
    nic='$nic',
    registration_nc='$registration_nc',
    registration_mc='$registration_mc',
    grade='$grade',
    contact='$contact',
    address='$address',
    civil_status='$civil_status',
    current_workplace='$current_workplace',
    current_workplace_enroll_date='$current_workplace_enroll_date',
    diploma_done=$diploma_done,
    diploma_school='$diploma_school',
    diploma_year='$diploma_year',
    bsc_done=$bsc_done,
    bsc_school='$bsc_school',
    bsc_year='$bsc_year',
    msc_done=$msc_done,
    msc_school='$msc_school',
    msc_year='$msc_year',
    enroll_date_spg='{$enroll_dates['enroll_date_spg']}',
    enroll_date_supra='{$enroll_dates['enroll_date_supra']}',
    enroll_date_1='{$enroll_dates['enroll_date_1']}',
    enroll_date_2='{$enroll_dates['enroll_date_2']}',
    enroll_date_3='{$enroll_dates['enroll_date_3']}',
    exp_institution='$exp_data',
    emergency_name='$emergency_name',
    emergency_relation='$emergency_relation',
    emergency_contact='$emergency_contact',
    emergency_to_spoke='$emergency_to_spoke',
    additional_notes='$additional_notes'";

if ($image_path != '') {
    $sql .= ", image='$image_path'";
}

$sql .= " WHERE id=$id";

// Execute
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Nurse updated successfully.'); window.location.href='dashboard.php';</script>";
} else {
    echo "Error updating nurse: " . $conn->error;
}
?>
