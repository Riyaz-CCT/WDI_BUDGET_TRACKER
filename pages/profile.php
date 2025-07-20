<?php require_once '../php/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FinTrack Pro Dashboard</title>

    <link rel="stylesheet" href="../css/profile.css" />
    <link rel="stylesheet" href="../css/sidebar.css" /> <!-- ✅ Sidebar styles -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
  </head>

  <body>
    <!-- ===================== SIDEBAR ===================== -->
    <?php include '../components/sidebar.php'; ?>

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
              <label>Target Saving</label>
              <p id="profileSaving">₹0.00</p>
            </div>
            <!-- <div class="profile-row">
              <label>Debt</label>
              <p id="profileDebt">₹0.00</p>
            </div> -->
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
          <input type="text" id="usernameInput"/>

          <label>Phone:</label>
          <input type="text" id="phoneInput"  />

          <label>Target Expense:</label>
          <input type="number" id="budgetInput"  />

          <label>Target Saving:</label>
          <input type="number" id="savingInput"  />

          <label>Debt:</label>
          <input type="number" id="debtInput"  />

          <button type="submit">Save Changes</button>
        </form>
      </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function () {
        let originalValues = {};

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

            $(".profile-name").text(data.name);
            $("#profileEmail").text(data.email);
            $("#profilePhone").text(data.phone);
            $("#profileBudget").text(`₹${data.target_expense}`);
            $("#profileSaving").text(`₹${data.target_saving}`);
            $("#profileDebt").text(`₹${data.debt}`);
            $(".member-since").text(`Member since ${data.member_since}`);

            originalValues = {
              name: data.name,
              phone: data.phone,
              target_expense: data.target_expense,
              target_saving: data.target_saving,
              debt: data.debt
            };
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
          $("#savingInput").val($("#profileSaving").text().replace("₹", ""));
          $("#debtInput").val($("#profileDebt").text().replace("₹", ""));
          $("#editModal").addClass("show");
        });

        // Close modal
        $(".close-btn").click(function () {
          $("#editModal").removeClass("show");
        });

        // Save changes — only send changed fields
        $("#editProfileForm").submit(function (e) {
          e.preventDefault();

          const currentValues = {
            name: $("#usernameInput").val(),
            phone: $("#phoneInput").val(),
            target_expense: $("#budgetInput").val(),
            target_saving: $("#savingInput").val(),
            debt: $("#debtInput").val()
          };

          const updatedData = {};
          for (const key in currentValues) {
            const original = originalValues[key]?.toString().trim();
            const current = currentValues[key]?.toString().trim();
            if (current !== "" && current !== original) {
              updatedData[key] = current;
            }
          }

          if (Object.keys(updatedData).length === 0) {
            alert("⚠️ No changes detected.");
            return;
          }

          $.ajax({
            url: "../php/update_profile.php",
            method: "POST",
            dataType: "json",
            data: updatedData,
            success: function (response) {
              if (response.success) {
                alert("✅ Profile updated successfully.");

                if (updatedData.name) $(".profile-name").text(updatedData.name);
                if (updatedData.phone) $("#profilePhone").text(updatedData.phone);
                if (updatedData.target_expense) $("#profileBudget").text(`₹${updatedData.target_expense}`);
                if (updatedData.target_saving) $("#profileSaving").text(`₹${updatedData.target_saving}`);
                if (updatedData.debt) $("#profileDebt").text(`₹${updatedData.debt}`);

                $("#editModal").removeClass("show");
              } else {
                alert("⚠️ No updates applied.");
              }
            },
            error: function () {
              alert("❌ Failed to send changes.");
            }
          });
        });

        // Confirm delete
        $("#deleteBtn").click(function () {
          const confirmed = confirm("⚠️ Are you sure you want to delete your account?");
          if (confirmed) {
            window.location.href = "../php/delete_account.php";
          }
        });
      });
    </script>
  </body>
</html>
