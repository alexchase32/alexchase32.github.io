<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id']);
    $assignment = trim($_POST['assignment']);

    if (!empty($student_id) && !empty($assignment)) {
        // Create a folder to store submissions if not exists
        $dir = "submissions";
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        // Create filename with student ID and timestamp
        $filename = $dir . "/" . $student_id . "_" . date("Ymd_His") . ".txt";

        // Save content
        $content = "Student ID: $student_id\n\nAssignment:\n$assignment";
        file_put_contents($filename, $content);

        echo "<h2>Submission Successful</h2>";
        echo "<p>Thank you, your assignment has been submitted.</p>";
    } else {
        echo "<h2>Error</h2>";
        echo "<p>Please fill in all fields.</p>";
    }
} else {
    echo "<h2>Invalid Request</h2>";
}
?>
