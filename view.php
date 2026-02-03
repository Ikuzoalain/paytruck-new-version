<?php
session_start();
include 'db.php'; // your database connection file

// Check if employee is logged in
if(!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit;
}

$emp_id = $_SESSION['emp_id'];

// Get employee info
$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_code=?");
$stmt->bind_param("s", $emp_id);
$stmt->execute();
$emp = $stmt->get_result()->fetch_assoc();

// Get overtime records
$stmt = $conn->prepare("SELECT * FROM overtime WHERE employee_id=(SELECT id FROM employees WHERE employee_code=?) ORDER BY overtime_date DESC");
$stmt->bind_param("s", $emp_id);
$stmt->execute();
$ot = $stmt->get_result();
$overtime_records = $ot->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Employee Dashboard | HR System</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}
body{background:#f4f4f4;color:#000}
.navbar{display:flex;justify-content:space-between;padding:15px 40px;background:#111;align-items:center;color:#fff}
.navbar button.logout-btn{margin-left:20px;padding:5px 10px;border:none;border-radius:4px;background:#d9534f;color:#fff;font-weight:bold;cursor:pointer}
.navbar button.logout-btn:hover{background:#c9302c}
.hero{min-height:100vh;background:#222;padding:40px 60px;color:#fff}
.wrapper{display:flex;gap:30px;margin-top:20px}
.box{background:#fff;color:#000;padding:25px;border-radius:8px;box-shadow:0 0 15px rgba(0,0,0,.3);width:420px}
.table-box{background:#fff;color:#000;padding:20px;border-radius:8px;box-shadow:0 0 15px rgba(0,0,0,.3);width:100%}
table{width:100%;border-collapse:collapse}
th,td{padding:10px;border-bottom:1px solid #ddd;text-align:center}
th{background:#f1f1f1}
tr:hover{background:#f9f9f9}
</style>
</head>
<body>

<div class="navbar">
  <strong>HR SYSTEM</strong>
  <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</div>

<section class="hero">
  <h2 style="margin-bottom:20px;color:#ff9800">Employee Dashboard</h2>

  <div class="wrapper">
    <div class="box">
      <h3>Your Information</h3>
      <p>
        <strong>ID:</strong> <?php echo htmlspecialchars($emp['employee_code']); ?><br>
        <strong>Name:</strong> <?php echo htmlspecialchars($emp['first_name'].' '.$emp['second_name']); ?><br>
        <strong>Phone:</strong> <?php echo htmlspecialchars($emp['phone']); ?><br>
        <strong>Email:</strong> <?php echo htmlspecialchars($emp['email']); ?><br>
        <strong>Department:</strong> <?php echo htmlspecialchars($emp['department']); ?><br>
        <strong>Position:</strong> <?php echo htmlspecialchars($emp['position']); ?><br>
        <strong>Salary:</strong> <?php echo htmlspecialchars($emp['salary']); ?>
      </p>
    </div>

    <div class="table-box">
      <h3>Your Overtime Records</h3>
      <!-- Calendar + Search -->
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <div>
          <label>From:</label><input type="date" id="fromDate" onchange="filterOT()">
          <label>To:</label><input type="date" id="toDate" onchange="filterOT()">
        </div>
        <div>
          <input type="text" id="otSearch" placeholder="Search by Date..." oninput="filterOT()" style="padding:5px 10px;border-radius:4px;border:1px solid #ccc;">
        </div>
      </div>
      <table>
        <thead>
          <tr><th>Date</th><th>Hours</th><th>OT Pay</th><th>Total Salary</th></tr>
        </thead>
        <tbody id="otTable">
          <?php foreach($overtime_records as $o): ?>
            <tr>
              <td><?php echo htmlspecialchars($o['overtime_date']); ?></td>
              <td><?php echo htmlspecialchars($o['overtime_hours']); ?></td>
              <td><?php echo htmlspecialchars($o['overtime_pay']); ?></td>
              <td><?php echo htmlspecialchars($o['hourly_rate']*$o['overtime_hours'] + $emp['salary']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<script>
// Filter table by date or search
function filterOT(){
  let from = document.getElementById('fromDate').value;
  let to = document.getElementById('toDate').value;
  let search = document.getElementById('otSearch').value.toLowerCase();
  let table = document.getElementById('otTable');
  Array.from(table.rows).forEach(row => {
    let date = row.cells[0].innerText;
    if((!from || date >= from) &&
       (!to || date <= to) &&
       (!search || date.toLowerCase().includes(search))) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}
</script>

</body>
</html>
