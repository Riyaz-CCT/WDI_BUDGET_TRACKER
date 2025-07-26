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
      <li >
        <a href="dashboard.php"><i class="fas fa-chart-line"></i><span>Dashboard</span></a>
      </li>
      <li>
        <a href="categories.php"><i class="fas fa-list-alt"></i><span>Expenses</span></a>
      </li>
      <li class="active">
        <a href="#"><i class="fas fa-user"></i><span>My Profile</span></a>
      </li>
      <li class="logout">
        <a href="../php/logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
      </li>
    </ul>
  </div>

    <!-- ===================== MAIN ===================== -->
    <div class="main-container">
      <!-- <div class="header--wrapper">
        <div class="header--title"><h2>My Profile</h2></div>
      </div> -->

      <div class="profile-card">
        <div class="profile-left">
          <img src="../assests/profile.png" alt="Profile" class="profile-avatar" />
          <h3 class="profile-name">Loading...</h3>
          <p class="member-since">Member since --</p>

          <div class="profile-details">
            <div class="profile-row">
              <label>Email</label>
              <p id="profileEmail">Loading...</p>
            </div>
            <div class="profile-row">
              <label>Phone</label>
              <p id="profilePhone">Loading...</p>
            </div>
            <div class="profile-row">
              <label>Target Expense</label>
              <p id="profileBudget">₹0.00</p>
            </div>
            <div class="profile-row">
              <label>Debt</label>
              <p id="profileDebt">₹0.00</p>
            </div>
          </div>

          <div class="profile-button">
            <button class="edit-btn" id="editBtn">
              <i class="fa-solid fa-pen"></i> Edit Profile
            </button>

            <button class="delete-btn" id="deleteBtn">
              <i class="fa-solid fa-trash"></i> Delete Account
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ===================== EDIT MODAL ===================== -->
    <div id="editModal">
      <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Edit Profile</h2>
        <form id="editProfileForm">
          <label>Username:</label>
          <input type="text" id="usernameInput" required />

          <label>Phone:</label>
          <input type="text" id="phoneInput" required />

          <label>Target Expense:</label>
          <input type="number" id="budgetInput" required />

          <label>Debt:</label>
          <input type="number" id="debtInput" required />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
        // Fetch profile data
        $.ajax({
          url: "../php/get_profile_data.php",
          method: "GET",
          dataType: "json",
          success: function (data) {
            if (data.error) {
              alert(data.error);
              return;
            }

            // Populate profile info
            $(".profile-name").text(data.name);
            $("#profileEmail").text(data.email);
            $("#profilePhone").text(data.phone);
            $("#profileBudget").text(`₹${data.target_expense}`);
            $("#profileDebt").text(`₹${data.debt}`);
            $(".member-since").text(`Member since ${data.member_since}`);
          },
          error: function () {
            alert("Failed to load profile data.");
          }
        });

        // Show edit modal
        $("#editBtn").click(function () {
          $("#usernameInput").val($(".profile-name").text());
          $("#phoneInput").val($("#profilePhone").text());
          $("#budgetInput").val($("#profileBudget").text().replace("₹", ""));
          $("#debtInput").val($("#profileDebt").text().replace("₹", ""));
          $("#editModal").addClass("show");
        });

        // Close modal
        $(".close-btn").click(function () {
          $("#editModal").removeClass("show");
        });

        // Save changes (frontend only for now)
        $("#editProfileForm").submit(function (e) {
          e.preventDefault();
          $(".profile-name").text($("#usernameInput").val());
          $("#profilePhone").text($("#phoneInput").val());
          $("#profileBudget").text(`₹${$("#budgetInput").val()}`);
          $("#profileDebt").text(`₹${$("#debtInput").val()}`);
          $("#editModal").removeClass("show");
        });

        // Confirm delete action
        $("#deleteBtn").click(function () {
          const confirmed = confirm("⚠️ Are you sure you want to delete your account? This action cannot be undone.");
          if (confirmed) {
            window.location.href = "../php/send_delete_otp.php";
;
          }
          
        });
      });
    </script>
  </body>
</html>