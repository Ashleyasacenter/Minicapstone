<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'functions.php';

requireLogin();

// Get stats for dashboard
$student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$violation_count = $pdo->query("SELECT COUNT(*) FROM violations")->fetchColumn();
$pending_violations = $pdo->query("SELECT COUNT(*) FROM violations WHERE status = 'pending'")->fetchColumn();

// Get student violations
$student_violations = $pdo->query("SELECT v.*, s.student_id, s.first_name, s.last_name, s.year_level 
                                    FROM violations v 
                                    JOIN students s ON v.student_id = s.student_id 
                                    ORDER BY v.violation_date DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="style1.css">

	<title>Violation Management System</title>
</head>
<body>

	<!-- SIDEBAR -->
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="cics.png" alt="Logo" class="logo-img"> <!-- Replace with your logo -->
			<span class="text">Violation System</span>
		</a>
		<ul class="side-menu top">
			<li class="active">
				<a href="#">
					<i class='bx bxs-dashboard'></i>
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-group'></i>
					<span class="text">Student</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-show-alert'></i>
					<span class="text">Violation</span>
				</a>
			</li>
			<li>
				<a href="#">
					<i class='bx bxs-user-account'></i>
					<span class="text">Profile</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog'></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="logout.php" class="logout"> <!-- Changed from # to logout.php -->
					<i class='bx bxs-log-out-circle'></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu'></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
				</div>
			</form>
			<a href="#" class="notification">
				<i class='bx bxs-bell'></i>
			</a>
		</nav>
		<!-- NAVBAR -->

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right'></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-icon-whistle'></i>
					<span class="text">
						<p>Total Violations</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-group'></i>
					<span class="text">
						<p>Total Students</p>
					</span>
				</li>
				<li>
					<i class='bx bxs-user-account'></i>
					<span class="text">
						<p>Total Pending</p>
					</span>
				</li>
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3>Student Violation List</h3>
						<i class='bx bx-search'></i>
						<i class='bx bx-filter'></i>
					</div>
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Date</th>
								<th>Year Level</th> <!-- Changed from Time to Year Level -->
								<th>Violation</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody id="violation-list">
							<!-- Violations will be added here dynamically -->
						</tbody>
					</table>
				</div>
				<div class="todo">
					<div class="head">
						<h3>Add Student Violation</h3>
						<button id="add-violation-btn" class="add-btn"><i class='bx bx-plus'></i></button>
					</div>
					<div id="violation-form" style="display: none;">
						<form id="add-violation-form">
							<div class="form-group">
								<label for="student-id">Student ID:</label>
								<input type="text" id="student-id" required>
							</div>
							<div class="form-group">
								<label for="student-name">Name:</label>
								<input type="text" id="student-name" required>
							</div>
							<div class="form-group">
								<label for="violation-date">Date:</label>
								<input type="date" id="violation-date" required>
							</div>
							<div class="form-group">
								<label for="year-level">Year Level:</label>
								<select id="year-level" required>
									<option value="">Select Year Level</option>
									<option value="1">1st Year</option>
									<option value="2">2nd Year</option>
									<option value="3">3rd Year</option>
									<option value="4">4th Year</option>
								</select>
							</div>
							<div class="form-group">
								<label for="violation-type">Violation:</label>
								<select id="violation-type" required>
									<option value="">Select violation</option>
									<option value="Printed Inner">Printed Inner</option>
									<option value="Printed Socks">Printed Socks</option>
									<option value="Ordinary">Ordinary</option>
									<option value="No Inner">No Inner</option>
									<option value="No ID">No ID</option>
									<option value="No Logo">No Logo</option>
									<option value="No Socks/foot socks">No Socks/foot socks</option>
									<option value="Piercing">Piercing</option>
									<option value="Hair Cut">Hair Cut</option>
									<option value="Hair Tie">Hair Tie</option>
								</select>
							</div>
							<button type="submit" class="submit-btn">Add Violation</button>
						</form>
					</div>
				</div>
			</div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->
	
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Toggle violation form
			const addBtn = document.getElementById('add-violation-btn');
			const violationForm = document.getElementById('violation-form');
			
			addBtn.addEventListener('click', function() {
				violationForm.style.display = violationForm.style.display === 'none' ? 'block' : 'none';
			});
			
			// Handle form submission
			const violationFormElement = document.getElementById('add-violation-form');
			const violationList = document.getElementById('violation-list');
			
			violationFormElement.addEventListener('submit', function(e) {
				e.preventDefault();
				
				// Get form values
				const id = document.getElementById('student-id').value;
				const name = document.getElementById('student-name').value;
				const date = document.getElementById('violation-date').value;
				const yearLevel = document.getElementById('year-level').value; // Get the selected year level
				const violation = document.getElementById('violation-type').value;
				
				// Add to violation list
				const newRow = document.createElement('tr');
				newRow.innerHTML = `
					<td>${id}</td>
					<td>${name}</td>
					<td>${date}</td>
					<td>${yearLevel}</td> <!-- Display the year level -->
					<td>${violation}</td>
					<td><span class="status pending">Pending</span></td>
				`;
				
				violationList.appendChild(newRow);
				
				// Reset form
				violationFormElement.reset();
				violationForm.style.display = 'none';
				
				// You would typically also send this data to a server here
			});
		});
	</script>
	<script src="script1.js"></script>
</body>
</html>
