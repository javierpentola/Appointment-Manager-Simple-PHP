<?php
// index.php

// Database connection (in-memory SQLite for simplicity)
try {
    $db = new PDO('sqlite::memory:');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("CREATE TABLE appointments (id INTEGER PRIMARY KEY, name TEXT, email TEXT, date TEXT, time TEXT)");
} catch (PDOException $e) {
    die("Error connecting to the database: " . $e->getMessage());
}

// Handle form submissions for creating appointments
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_appointment'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $db->prepare("INSERT INTO appointments (name, email, date, time) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $date, $time]);
}

// Fetch all appointments
$appointments = $db->query("SELECT * FROM appointments")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            margin-bottom: 15px;
        }
        .form-control label {
            display: block;
            margin-bottom: 5px;
        }
        .form-control input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Appointment Management System</h1>
    <form method="post" action="">
        <div class="form-control">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-control">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-control">
            <label for="date">Date</label>
            <input type="date" id="date" name="date" required>
        </div>
        <div class="form-control">
            <label for="time">Time</label>
            <input type="time" id="time" name="time" required>
        </div>
        <button type="submit" name="create_appointment">Create Appointment</button>
    </form>

    <h2>Appointments List</h2>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
            <th>Time</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($appointments as $appointment): ?>
            <tr>
                <td><?= htmlspecialchars($appointment['name']) ?></td>
                <td><?= htmlspecialchars($appointment['email']) ?></td>
                <td><?= htmlspecialchars($appointment['date']) ?></td>
                <td><?= htmlspecialchars($appointment['time']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Basic form validation using JavaScript
    document.querySelector('form').addEventListener('submit', function (e) {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const date = document.getElementById('date').value;
        const time = document.getElementById('time').value;

        if (!name || !email || !date || !time) {
            alert('Please fill in all fields.');
            e.preventDefault();
        }
    });
</script>
</body>
</html>
