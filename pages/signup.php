<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="../css/login_styles.css" />
    
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
            <input
              type="text"
              name="phone"
              placeholder="Phone Number *"
              required
            />
          </div>
          <input type="email" name="email" placeholder="Email *" required />
          <div class="input-row">
            <input
              type="password"
              name="password"
              placeholder="Password *"
              required
            />
            <input
              type="password"
              name="repassword"
              placeholder="Re-enter Your Password *"
              required
            />
          </div>

          <div class="password-rules" id="password-rules">
            <p>Password must include:</p>
            <ul>
              <li id="rule-upper">*At least 1 uppercase letter</li>
              <li id="rule-lower">*At least 1 lowercase letter</li>
              <li id="rule-special">*One special character (!@$&)</li>
              <li id="rule-length">*Minimum 8 characters</li>
            </ul>
            <p id="password-error" style="color: red; display: none;">
              Please enter a valid password!
            </p>
          </div>

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

      const rules = {
        upper: document.getElementById("rule-upper"),
        lower: document.getElementById("rule-lower"),
        special: document.getElementById("rule-special"),
        length: document.getElementById("rule-length"),
      };

      const errorText = document.getElementById("password-error");

      function validatePassword(pw) {
        const hasUpper = /[A-Z]/.test(pw);
        const hasLower = /[a-z]/.test(pw);
        const hasSpecial = /[!@$&]/.test(pw);
        const hasLength = pw.length >= 8;

        // Update UI
        rules.upper.style.color = hasUpper ? "green" : "gray";
        rules.lower.style.color = hasLower ? "green" : "gray";
        rules.special.style.color = hasSpecial ? "green" : "gray";
        rules.length.style.color = hasLength ? "green" : "gray";

        return hasUpper && hasLower && hasSpecial && hasLength;
      }

      passwordInput.addEventListener("input", () => {
        const pw = passwordInput.value;
        validatePassword(pw);
      });

      form.addEventListener("submit", function (e) {
        const password = passwordInput.value;
        const repassword = repasswordInput.value;

        if (password !== repassword) {
          e.preventDefault();
          alert("Passwords do not match!");
          return;
        }

        if (!validatePassword(password)) {
          e.preventDefault();
          errorText.style.display = "block";
          return;
        }

        errorText.style.display = "none";
      });
    </script>
  </body>
</html>
