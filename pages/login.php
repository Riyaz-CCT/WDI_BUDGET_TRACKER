<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fintrack Login</title>
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
        <h1>Welcome To FinTrack Pro!</h1>
        <p class="subtext">Log in to continue your financial journey.</p>

        <form id="login-form" action="../php/login2.php" method="POST">
          <label for="email">Email <span>*</span></label>
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Type your email here"
            required
          />

          <label for="password">Password <span>*</span></label>
          <input
            type="password"
            name="password"
            id="password"
            placeholder="Please enter your password"
            required
          />

          <a href="forgot-password.html" class="forgot">Forgot Password?</a>

          <button type="submit" class="sign-in">Sign In</button>

          <p class="signup">
            Not a member yet? <a href="signup.php">Sign Up</a>
          </p>

          <!-- <button
            type="button"
            class="google-btn"
            onclick="alert('Google Sign-In coming soon!');"
          >
            <img
              src="https://cdn-icons-png.flaticon.com/512/300/300221.png"
              alt="Google Icon"
            />
            Sign In With Google
          </button> -->
        </form>
      </div>
    </div>

    <script>
      function togglePassword() {
        const pwd = document.getElementById("password");
        pwd.type = pwd.type === "password" ? "text" : "password";
      }
    </script>
  </body>
</html>