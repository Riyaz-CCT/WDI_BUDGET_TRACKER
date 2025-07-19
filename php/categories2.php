<?php
require_once '../php/auth.php';

// DB connection
$host = "localhost";
$user = "root";
$pass = "";
$db   = "fintrack_v2";
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
$user_id = 1; // Replace with session user ID when login is done

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date         = $_POST['expense-date'] ?? '';
    $category     = $_POST['expense-category'] ?? '';
    $newCategory  = trim($_POST['new-category'] ?? '');
    $item         = $_POST['expense-item'] ?? '';
    $description  = $_POST['expense-description'] ?? '';
    $type         = $_POST['transaction-type'] ?? '';
    $amount       = floatval($_POST['expense-amount'] ?? 0);
    $payment      = $_POST['payment-method'] ?? '';
    $receiptBlob  = NULL;

    if ($category === 'AddNew' && !empty($newCategory)) {
        $stmt = $conn->prepare("INSERT IGNORE INTO categories(name) VALUES (?)");
        $stmt->bind_param("s", $newCategory);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $newCategory);
    } else {
        $stmt = $conn->prepare("SELECT id FROM categories WHERE name = ?");
        $stmt->bind_param("s", $category);
    }
    $stmt->execute();
    $stmt->bind_result($category_id);
    $stmt->fetch();
    $stmt->close();

    if (isset($_FILES['expense-file']) && $_FILES['expense-file']['error'] == 0) {
        $receiptBlob = file_get_contents($_FILES['expense-file']['tmp_name']);
    }

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, category_id, item, description, type, amount, date, payment_method, receipt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $nullBlob = NULL;
    $stmt->bind_param("iisssdsss", $user_id, $category_id, $item, $description, $type, $amount, $date, $payment, $nullBlob);
    $stmt->send_long_data(8, $receiptBlob);
    $stmt->execute();
    $stmt->close();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT t.id, c.name AS category, t.item, t.description, t.type AS transaction_type, t.amount, t.date, t.payment_method FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? ORDER BY t.date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - FinTrack</title>
  <link rel="stylesheet" href="../css/categories_styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <div class="logo">
    <img src="../assests/budget.png" alt="Logo">
    <p>FinTrack Pro</p>
  </div>
  <ul class="menu">
    <li class="active"><a href="#"><i class="fas fa-chart-line"></i><span>Dashboard</span></a></li>
    <li><a href="#"><i class="fas fa-user"></i><span>My Profile</span></a></li>
    <li class="logout"><a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
  </ul>
</div>

<!-- Main Content -->
<div class="main--content">
  <div class="header--wrapper">
    <div class="header--title"><h2>Categories</h2></div>
    <div class="user--info">
      <div class="search--box">
        <i class="fa fa-search"></i>
        <input type="text" id="searchBox" placeholder="Search transactions..." />
      </div>
      <a href="#"><img src="../assests/profile.png" alt="profile picture" /></a>
    </div>
  </div>

  <div class="container">
    <div class="top-bar">
      <h2>Recent Expenses</h2>
      <button class="add-expense-btn" id="open-modal">
        <span class="plus-icon">+</span> <span style="color:white">Add New Expense</span>
      </button>
    </div>
    <table id="transactionTable">
      <thead>
        <tr>
          <th>Date</th>
          <th>Category</th>
          <th>Description</th>
          <th>Item</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Payment</th>
          <th>Receipt</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($transactions as $txn): ?>
        <tr>
          <td><?= htmlspecialchars($txn['date']) ?></td>
          <td><?= htmlspecialchars($txn['category']) ?></td>
          <td><?= htmlspecialchars($txn['description']) ?></td>
          <td><?= htmlspecialchars($txn['item']) ?></td>
          <td><?= htmlspecialchars($txn['transaction_type']) ?></td>
          <td style="color:#3949ab;font-weight:bold;">$<?= number_format($txn['amount'], 2) ?></td>
          <td><?= htmlspecialchars($txn['payment_method']) ?></td>
          <td><i class="fa fa-eye"></i></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Form -->
<div class="modal" id="expense-modal">
  <div class="modal-box">
    <span class="close-button" id="close-modal">&times;</span>
    <h2>Add New Expense Details</h2>
    <form method="POST" enctype="multipart/form-data">
      <label for="expense-date">Date *</label>
      <input type="date" name="expense-date" id="expense-date" required max="<?= date('Y-m-d') ?>">

      <label for="expense-category">Category *</label>
      <select name="expense-category" id="expense-category" required>
        <option value="">Select Category</option>
        <?php
          $catRes = $conn->query("SELECT name FROM categories ORDER BY name ASC");
          while ($cat = $catRes->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($cat['name']) . '">' . htmlspecialchars($cat['name']) . '</option>';
          }
        ?>
        <option value="AddNew">+ Add New Category</option>
      </select>

      <div id="new-category-div" style="display:none;">
        <input type="text" id="new-category-input" placeholder="New Category Name">
        <input type="hidden" name="new-category" id="hidden-new-category">
      </div>

      <label for="expense-item">Item *</label>
      <input type="text" name="expense-item" id="expense-item" required>

      <label for="expense-amount">Amount *</label>
      <input type="number" name="expense-amount" id="expense-amount" required>

      <label for="transaction-type">Transaction Type *</label>
      <select name="transaction-type" id="transaction-type" required>
        <option value="">Select Type</option>
        <option value="Expense">Expense</option>
        <option value="Income">Income</option>
      </select>

      <label for="payment-method">Payment Method *</label>
      <select name="payment-method" id="payment-method" required>
        <option value="">Select Method</option>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="UPI">UPI</option>
      </select>

      <label for="expense-file">Attach File</label>
      <input type="file" name="expense-file" id="expense-file" accept=".pdf,.json,.docx,.txt">

      <label for="expense-description">Description</label>
      <textarea name="expense-description" id="expense-description"></textarea>

      <div class="form-buttons">
        <button type="submit" class="save-button">Save</button>
        <button type="button" class="cancel-button" id="cancel-modal">Cancel</button>
      </div>
    </form>
  </div>
</div>

<script>
  $(function () {
    $('#open-modal').click(() => $('#expense-modal').addClass('show'));
    $('#close-modal, #cancel-modal').click(() => $('#expense-modal').removeClass('show'));
    $('#expense-category').change(function () {
      if (this.value === "AddNew") {
        $('#new-category-div').show();
        $('#new-category-input').attr('required', true);
      } else {
        $('#new-category-div').hide();
        $('#new-category-input').val('');
        $('#hidden-new-category').val('');
      }
    });
    $('form').submit(function () {
      if ($('#expense-category').val() === "AddNew") {
        $('#hidden-new-category').val($('#new-category-input').val());
      }
    });
  });
</script>
</body>
</html>
