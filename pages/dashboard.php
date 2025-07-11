<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FinTrack Pro Dashboard</title>

  <link rel="stylesheet" href="../css/dashboard_styles.css" />
  <link rel="stylesheet" href="../css/responsive_dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
  <!-- ===================== SIDEBAR STARTS HERE ===================== -->
  <div class="sidebar">
    <div class="logo">
      <img src="../assests/budget.png" alt="Logo" />
      <p>FinTrack Pro</p>
    </div>
    <ul class="menu">
      <li class="active">
        <a href="#"><i class="fas fa-chart-line"></i><span>Dashboard</span></a>
      </li>
      <li>
        <a href="categories.html"><i class="fas fa-list-alt"></i><span>Expenses</span></a>
      </li>
      <li>
        <a href="#"><i class="fas fa-user"></i><span>My Profile</span></a>
      </li>
      <li class="logout">
        <a href="#" id="logout-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
      </li>
    </ul>
  </div>
  <!-- ===================== SIDEBAR ENDS HERE ===================== -->

  <!-- ===================== MAIN STARTS HERE ===================== -->
  <div class="main-container">
    <div class="header--wrapper">
      <button id="toggle-btn">☰</button>
      <div class="header--title">
        <h1>Dashboard</h1>
      </div>
      <div class="user--info">
        <a href="profile.html"><img src="../assests/profile.png" alt="profile picture" /></a>
      </div>
    </div>

    <div class="main--content">
      <!-- ========== SECTION 1: Stats + Progress Bar ========== -->
      <div class="section-1">
        <div class="stats-cards-container">
          <div class="stats-top-section">
            <div class="overview-heading">
              <h2 class="card-title">Monthly Overview</h2>
            </div>
            <div class="action-buttons">
              <button class="rounded-btn">Import Data</button>
              <button class="rounded-btn">Export Data</button>
              <button class="rounded-btn">Download Reports</button>
            </div>
          </div>

          <div class="stats-cards-slider">
            <div class="stats-cards">
              <p>Income</p>
              <span id="income-value">$0</span>
              <p class="growth up" style="color:#2e7d32">+1.97%</p>
            </div>
            <div class="stats-cards">
              <p>Expense</p>
              <span id="expense-value">$0</span>
              <p class="growth down" style="color:#ef5350">-7.35%</p>
            </div>
            <div class="stats-cards">
              <p>Savings</p>
              <span id="savings-value">$0</span>
              <p class="growth up" style="color:#ef5350">-5.30%</p>
            </div>
            <div class="stats-cards">
              <p>Balance</p>
              <span id="balance-value">$15,000</span>
              <p class="growth up" style="color:#2e7d32">+1.97%</p>
            </div>
          </div>
        </div>

        <!-- ========== New Section: Progress Bars ========== -->
        <div class="progress-bar-section">
          <h3 class="progress-header">Goal Progress</h3>

          <div class="goal-progress-wrapper">
            <!-- Budget Goal -->
            <div class="progress-row">
              <div class="progress-info">
                <p class="progress-title">Expense Budget</p>
                <span class="progress-percent" id="progress-expense-value">0%</span>
              </div>
              <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 0%;" data-percent="0" data-type="expense"></div>
              </div>
              <p class="progress-subtext">0% of your monthly budget used.</p>
            </div>

            <!-- Savings Target -->
            <div class="progress-row">
              <div class="progress-info">
                <p class="progress-title">Saving Target</p>
                <span class="progress-percent" id="progress-savings-value">0%</span>
              </div>
              <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 0%;" data-percent="0" data-type="saving"></div>
              </div>
              <p class="progress-subtext">You're halfway to your savings goal.</p>
            </div>
          </div>
        </div>
      </div>
      <!-- ========== SECTION 1 ENDS HERE ========== -->

      <!-- ========== SECTION 2: Charts ========== -->
      <div class="section-2">
        <div class="graph--container-1">
          <div class="chart-dropdown">
            <select id="rangeSelect" onchange="handleRangeChange(this.value)">
              <option value="monthly" selected>Monthly</option>
              <option value="weekly">Weekly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          <canvas id="barChart" class="chart-canvas"></canvas>
          <canvas id="lineChart" class="chart-canvas" style="display: none;"></canvas>
          <canvas id="altChart" class="chart-canvas" style="display: none;"></canvas>
        </div>

        <div class="graph--container-2">
          <div class="chart-dropdown">
            <select id="pieRangeSelect" onchange="handlePieRangeChange(this.value)">
              <option value="summary" selected>Summary</option>
              <option value="method">Method</option>
              <option value="category">Categories</option>
            </select>
          </div>
          <canvas id="summaryPieChart" class="chart-canvas"></canvas>
        </div>
      </div>

      <!-- ========== SECTION 3: Table ========== -->
      <div class="section-3">
        <div class="graph--container-3">
          <div class="chart-dropdown">
            <select id="bar2RangeSelect" onchange="handleBar2RangeChange(this.value)">
              <option value="monthly" selected>Monthly</option>
              <option value="weekly">Weekly</option>
              <option value="yearly">Yearly</option>
            </select>
          </div>
          <canvas id="barChart2" class="chart-canvas"></canvas>
        </div>

        <div class="tabular--wrapper">
          <h3 class="main--title">Recent Transactions</h3>
          <div class="table--container">
            <table>
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody></tbody>
              <tfoot></tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- ========== SECTION 3 ENDS HERE ========== -->
    </div>
  </div>

  <!-- ===================== SCRIPTS ===================== -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script src="../js/script.js"></script>
  <script src="../js/chart.js"></script>
  <script src="../js/goals.js"></script>
</body>

</html>
