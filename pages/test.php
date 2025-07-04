<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Charts Only - FinTrack Pro</title>

  <link rel="stylesheet" href="../css/dashboard_styles.css" />
  <link rel="stylesheet" href="../css/responsive_dashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>

  <!-- ========== SECTION 2: Charts Only ========== -->
  <div class="section-2">
    <!-- Bar/Line/Alt Chart -->
    <div class="graph--container-1">
      <div class="chart-dropdown">
        <select id="rangeSelect">
          <option value="monthly" selected>Monthly</option>
          <option value="weekly">Weekly</option>
          <option value="yearly">Yearly</option>
        </select>
      </div>
      <canvas id="barChart"></canvas>
      <canvas id="lineChart" style="display: none;"></canvas>
      <canvas id="altChart" style="display: none;"></canvas>
    </div>

    <!-- Pie Chart -->
    <div class="graph--container-2">
      <div class="chart-dropdown">
        <select id="pieRangeSelect">
          <option value="monthly" selected>Summary</option>
          <option value="weekly">Method</option>
          <option value="yearly">Categories</option>
        </select>
      </div>
      <canvas id="summaryPieChart" class="chart-canvas"></canvas>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="../js/chart2.js"></script>
</body>

</html>
