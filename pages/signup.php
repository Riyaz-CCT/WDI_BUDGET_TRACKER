<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/login_styles.css" />
    <!-- Font Awesome  -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <style>
      /* Optional fade animation for popup */
      #password-popup {
        display: none;
        background-color: #f9f9f8ff;
        color: #9b0101ff;
        border: 1px solid #430101ff;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        position: fixed;
        z-index: 1000;
        max-width: 300px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
      }
    </style>
  </head>

  <body>
    <div class="container">
      <!-- Left Section -->
      <div class="left-section">
        <div class="expenses-card-left">
          <div class="expense-title">
            <span class="icon">📊</span> May 2025 Expenses
          </div>
          <div class="expense-amount">$2,000</div>
          <div class="expense-change"><span>⬆️</span> 5%</div>

          <div class="graph-container">
            <div class="bar" style="--height: 60%"></div>
            <div class="bar" style="--height: 35%"></div>
            <div class="bar" style="--height: 45%"></div>
            <div class="bar today" style="--height: 80%"></div>
            <div class="bar" style="--height: 55%"></div>
            <div class="bar" style="--height: 30%"></div>
            <div class="bar" style="--height: 40%"></div>
          </div>

          <div class="days-labels">
            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span>
            <span>Fri</span><span>Sat</span><span>Sun</span>
          </div>
        </div>

        <div class="expenses-card-right">
          <div class="expense-title">
            <span class="icon">📊</span> June 2025 Expenses
          </div>
          <div class="expense-amount">$12,000</div>
          <div class="expense-change"><span>⬆️</span> 12%</div>

          <div class="graph-container">
            <div class="bar" style="--height: 60%"></div>
            <div class="bar" style="--height: 35%"></div>
            <div class="bar" style="--height: 45%"></div>
            <div class="bar today" style="--height: 80%"></div>
            <div class="bar" style="--height: 55%"></div>
            <div class="bar" style="--height: 30%"></div>
            <div class="bar" style="--height: 40%"></div>
          </div>

          <div class="days-labels">
            <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span>
            <span>Fri</span><span>Sat</span><span>Sun</span>
          </div>
        </div>
      </div>

      <!-- Right Section -->
      <div class="right-section">
        <div class="logo">
          <img src="../assests/budget.png" alt="logo" />
          <span>FinTrack Pro</span>
        </div>
        <h1>Join the FinTrack Pro Family!</h1>
        <p class="subtext">Log in to continue your financial journey.</p>

        <form id="signup-form" action="../php/signup2.php" method="POST">
          <div class="input-row">
            <input type="text" name="name" placeholder="Full Name *" required />
            <input type="text" name="phone" placeholder="Phone Number *" required />
          </div>

          <input type="email" name="email" placeholder="Email *" required />

          <div class="input-row">
            <div style="position: relative; width: 100%;">
              <input
                type="password"
                name="password"
                placeholder="Password *"
                required
                style="width: 100%; padding-right: 40px;"
              />
              <i
                class="fa-solid fa-eye toggle-password"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                data-target="password"
              ></i>
            </div>
            <div style="position: relative; width: 100%;">
              <input
                type="password"
                name="repassword"
                placeholder="Re-enter Your Password *"
                required
                style="width: 100%; padding-right: 40px;"
              />
              <i
                class="fa-solid fa-eye toggle-password"
                style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;"
                data-target="repassword"
              ></i>
            </div>
          </div>

          <!-- Popup for password validation -->
          <div id="password-popup"></div>

          <button type="submit" class="sign-in">Sign Up!</button>
          <p class="login-text">
            Already have an account? <a href="login.php">Log In</a>
          </p>
        </form>
      </div>
    </div>

    <!-- JavaScript -->
    <script>
      const passwordInput = document.querySelector('input[name="password"]');
      const repasswordInput = document.querySelector('input[name="repassword"]');
      const form = document.getElementById("signup-form");
      const popup = document.getElementById("password-popup");

      function getPasswordIssues(pw) {
        const issues = [];
        if (!/[A-Z]/.test(pw)) issues.push("Must include at least 1 uppercase letter.");
        if (!/[a-z]/.test(pw)) issues.push("Must include at least 1 lowercase letter.");
        if (!/[!@$&]/.test(pw)) issues.push("Must include 1 special character (!, @, $, &).");
        if (pw.length < 8) issues.push("Must be at least 8 characters long.");
        return issues;
      }

      function showPopup(issues, inputField) {
        const rect = inputField.getBoundingClientRect();
        popup.innerHTML = issues.join("<br>");
        popup.style.display = "block";
        popup.style.opacity = "1";
        popup.style.top = `${rect.bottom + 10}px`;
        popup.style.left = `${rect.left}px`;
      }

      function hidePopup() {
        popup.style.opacity = "0";
        setTimeout(() => {
          popup.style.display = "none";
        }, 300);
      }

      passwordInput.addEventListener("blur", () => {
        const pw = passwordInput.value;
        const issues = getPasswordIssues(pw);
        if (issues.length > 0) {
          showPopup(issues, passwordInput);
        } else {
          hidePopup();
        }
      });

      passwordInput.addEventListener("input", () => {
        hidePopup();
      });

      form.addEventListener("submit", function (e) {
        const password = passwordInput.value;
        const repassword = repasswordInput.value;

        if (password !== repassword) {
          e.preventDefault();
          alert("Passwords do not match!");
          return;
        }

        const issues = getPasswordIssues(password);
        if (issues.length > 0) {
          e.preventDefault();
          showPopup(issues, passwordInput);
          return;
        }

        hidePopup();
      });

      const toggleIcons = document.querySelectorAll(".toggle-password");

      toggleIcons.forEach((icon) => {
        icon.addEventListener("click", () => {
          const targetName = icon.getAttribute("data-target");
          const input = document.querySelector(`input[name="${targetName}"]`);

          if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
          } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
          }
        });
      });
    </script>
  </body>
</html>