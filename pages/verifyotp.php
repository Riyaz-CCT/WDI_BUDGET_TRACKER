<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Verify OTP</title>
    <link rel="stylesheet" href="../css/login_styles.css" />
    <style>
      input[type="number"]::-webkit-inner-spin-button,
      input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <!-- Left Section (same as other pages) -->
      <div class="left-section">
        <div class="expenses-card-left">
          <div class="expense-title">
            <span class="icon">📊</span> May 2025 Expenses
          </div>
          <div class="expense-amount">₹2,000</div>
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
            <span>Mon</span><span>Tue</span><span>Wed</span> <span>Thu</span
            ><span>Fri</span><span>Sat</span><span>Sun</span>
          </div>
        </div>

        <div class="expenses-card-right">
          <div class="expense-title">
            <span class="icon">📊</span> June 2025 Expenses
          </div>
          <div class="expense-amount">₹12,000</div>
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
            <span>Mon</span><span>Tue</span><span>Wed</span> <span>Thu</span
            ><span>Fri</span><span>Sat</span><span>Sun</span>
          </div>
        </div>
      </div>

      <!-- Right Section -->
      <div class="right-section">
        <div class="logo">
          <img src="img/money-bag.png" alt="logo" />
          <span>FinTrack Pro</span>
        </div>
        <h1 class="reset">Verify OTP</h1>

        <!-- ✅ OTP verification form -->
        <form class="reset-form" action="../php/verifyotp2.php" method="POST">
          <label>Enter OTP <span>*</span></label>
          <input
            type="number"
            name="otp"
            placeholder="Enter the 6-digit OTP"
            required
            maxlength="6"
            minlength="6"
          />
          <button type="submit" class="submit">Verify</button>
        </form>
      </div>
    </div>
  </body>
</html>