<?php require_once '../php/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FinTrack Pro Dashboard</title>

    <link rel="stylesheet" href="../css/profile.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>

  <body>
    <!-- ===================== SIDEBAR ===================== -->
    <div class="sidebar">
      <div class="logo">
        <img src="../assests/budget.png" alt="Logo" />
        <p>FinTrack Pro</p>
      </div>
      <ul class="menu">
        <li>
          <a href="dashboard.php"
            ><i class="fas fa-chart-line"></i><span>Dashboard</span></a
          >
        </li>
        <li>
          <a href="categories.php"
            ><i class="fas fa-list-alt"></i><span>Expenses</span></a
          >
        </li>
        <li class="active">
          <a href="profile.php"
            ><i class="fas fa-user"></i><span>My Profile</span></a
          >
        </li>
        <li class="logout">
          <a href="../php/logout.php" id="logout-link"
            ><i class="fas fa-sign-out-alt"></i><span>Logout</span></a
          >
        </li>
      </ul>
    </div>

    <!-- ===================== MAIN ===================== -->
    <div class="main-container">
      <h2 class="profile-heading">Profile</h2>
      <div class="profile-card">
        <div class="profile-left">
          <img
            src="../assests/profile.png"
            alt="Profile"
            class="profile-avatar"
          />
          <h3 class="profile-name">John Doe</h3>
          <p class="member-since">Member since Jan 2024</p>

          <div class="profile-details">
            <div class="profile-row">
              <label>Email</label>
              <p id="profileEmail">john@example.com</p>
            </div>
            <div class="profile-row">
              <label>Phone</label>
              <p id="profilePhone">+1 98765 123345</p>
            </div>
            <div class="profile-row">
              <label>Monthly Budget</label>
              <p id="profileBudget">₹4,500</p>
            </div>
            <div class="profile-row">
              <label>Preferred Currency</label>
              <p id="profileCurrency">INR (₹)</p>
            </div>
          </div>

          <div class="profile-button">
            <button class="edit-btn" id="editBtn">
              <i class="fa-solid fa-pen"></i> Edit Profile
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===================== EDIT MODAL ===================== -->
    <div id="editModal" class="modal">
      <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Edit Profile</h2>
        <form id="editProfileForm">
          <label>Email:</label>
          <input type="email" id="emailInput" required />

          <label>Phone:</label>
          <input type="text" id="phoneInput" required />

          <label>Monthly Budget:</label>
          <input type="text" id="budgetInput" required />

          <label>Preferred Currency:</label>
          <input type="text" id="currencyInput" required />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
        // Show modal
        $("#editBtn").click(function () {
          $("#emailInput").val($("#profileEmail").text());
          $("#phoneInput").val($("#profilePhone").text());
          $("#budgetInput").val(
            $("#profileBudget").text().replace("$", "").replace(",", "")
          );
          $("#currencyInput").val($("#profileCurrency").text());
          $("#editModal").fadeIn();
        });

        // Close modal
        $(".close-btn").click(function () {
          $("#editModal").fadeOut();
        });

        // Update profile details
        $("#editProfileForm").submit(function (e) {
          e.preventDefault();
          $("#profileEmail").text($("#emailInput").val());
          $("#profilePhone").text($("#phoneInput").val());
          $("#profileBudget").text(`$${$("#budgetInput").val()}`);
          $("#profileCurrency").text($("#currencyInput").val());
          $("#editModal").fadeOut();
        });
      });
    </script>
  </body>
</html>
