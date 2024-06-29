<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "campaign_feedback";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);
    $feedback = htmlspecialchars($_POST["feedback"]);

    $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $feedback);

    if ($stmt->execute() === TRUE) {
        echo "Feedback saved successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback Form</title>
    <link rel="stylesheet" href="style.css"/>
</head>
<body>
    <section id="feedback">
    <h2>Feedback Form</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="row">
        <label for="name" class="">Name:</label>
        <input type="text" name="name" required><br><br>
        </div>
        <div class="row">
        <label for="email" class="">Email:</label>
        <input type="email" name="email" required><br><br>
        </div>
        <div class="row">
        <label for="feedback" class="">Feedback:</label>
        <textarea name="feedback" required></textarea><br><br>
        </div>
        <input type="submit" value="Submit">
    </form>
    </section>
    <section id="list">
    <h2>Feedback</h2>
    <?php
    $sql = "SELECT id, name, email, feedback, submission_date FROM feedback";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='feedback-entry'><p id='id'>" . $row["id"]. "</p><p id='username'>" . $row["name"]. "</p><p id='email'>" . $row["email"]. "</p><p id='feedbacks'>" . $row["feedback"]. "</p><p id='time'>" . $row["submission_date"]. "</p></div>";
        }
    } else {
        echo "No feedbacks yet, <br/> be the first to give";
    }

    $conn->close();
    ?>
    </section>
</body>
</html>