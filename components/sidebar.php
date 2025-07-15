<link rel="stylesheet" href="../css/sidebar.css" />
<div class="sidebar">
  <div class="logo">
    <img src="../assests/budget.png" alt="Logo" />
    <p>FinTrack Pro</p>
  </div>
  <ul class="menu">
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">
      <a href="dashboard.php"><i class="fas fa-chart-line"></i><span>Dashboard</span></a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'categories.php' ? 'active' : ''; ?>">
      <a href="categories.php"><i class="fas fa-list-alt"></i><span>Expenses</span></a>
    </li>
    <li class="<?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
      <a href="profile.php"><i class="fas fa-user"></i><span>My Profile</span></a>
    </li>
    <li class="logout">
      <a href="../php/logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
    </li>
  </ul>
</div>
