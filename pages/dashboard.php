<?php require_once '../php/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>FinTrack Pro Dashboard</title>

  <link rel="stylesheet" href="../css/dashboard_styles.css" />
  <link rel="stylesheet" href="../css/responsive_dashboard.css" />
  <link rel="stylesheet" href="../css/sidebar.css" /> <!-- ✅ Include sidebar styles -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
  <!-- ===================== SIDEBAR (Reusable) ===================== -->
  <?php include '../components/sidebar.php'; ?>

  <!-- ===================== MAIN STARTS HERE ===================== -->
  <div class="main-container">
    <div class="header--wrapper">
      <button id="toggle-btn">☰</button>
      <div class="header--title">
        <h1>Dashboard</h1>
      </div>
      <div class="user--info">
        <a href="profile.php">
          <img src="../assests/profile.png" alt="profile picture" />
        </a>
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
              <form method="post" action="../php/get_export_data.php" style="display: inline;">
                <button class="rounded-btn">Export Data</button>
              </form>
              <a href="monthly_report.php" class="rounded-btn" style="text-decoration:none; display:inline-block;">View Report</a>
            </div>
          </div>

          <div class="stats-cards-slider">
            <div class="stats-cards">
              <p>Income</p>
              <span id="income-value">₹0</span>
              <p class="growth up" style="color:#2e7d32">+1.97%</p>
            </div>
            <div class="stats-cards">
              <p>Expense</p>
              <span id="expense-value">₹0</span>
              <p class="growth down" style="color:#ef5350">-7.35%</p>
            </div>
            <div class="stats-cards">
              <p>Savings</p>
              <span id="savings-value">₹0</span>
              <p class="growth up" style="color:#ef5350">-5.30%</p>
            </div>
            <div class="stats-cards">
              <p>Debt</p>
              <span id="debt-value">₹0</span>
              <p class="growth up" style="color:#2e7d32">-1.97%</p>
            </div>
          </div>
        </div>

        <!-- ========== Progress Bars ========== -->
        <div class="progress-bar-section">
          <h3 class="progress-header">Goal Progress</h3>
          <div class="goal-progress-wrapper">
            <div class="progress-row">
              <div class="progress-info">
                <p class="progress-title">Expense Budget</p>
                <span class="progress-percent" id="progress-expense-value">0%</span>
              </div>
              <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 0%;" data-percent="0" data-type="expense"></div>
              </div>
              <p class="progress-subtext">This month’s expense progress.</p>
            </div>

            <div class="progress-row">
              <div class="progress-info">
                <p class="progress-title">Saving Target</p>
                <span class="progress-percent" id="progress-savings-value">0%</span>
              </div>
              <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: 0%;" data-percent="0" data-type="saving"></div>
              </div>
              <p class="progress-subtext">Progress toward your savings plan.</p>
            </div>
          </div>
        </div>
      </div>

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
