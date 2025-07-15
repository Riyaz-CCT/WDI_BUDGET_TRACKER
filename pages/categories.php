<?php
// Database connection
$mysqli = new mysqli("localhost", "root", "", "fintrack_v2");
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

// Handle AJAX POST request to insert new category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category_ajax'])) {
  $name = trim($_POST['name']);
  if ($name !== "") {
    $stmt = $mysqli->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
      $insert = $mysqli->prepare("INSERT INTO categories (name) VALUES (?)");
      $insert->bind_param("s", $name);
      if ($insert->execute()) {
        echo json_encode(["success" => true]);
        exit;
      } else {
        echo json_encode(["success" => false, "error" => $insert->error]);
        exit;
      }
    } else {
      echo json_encode(["success" => true, "message" => "Category already exists"]);
      exit;
    }
  }
  echo json_encode(["success" => false, "error" => "Empty category name"]);
  exit;
}

// Fetch categories
$categories = [];
$result = $mysqli->query("SELECT name FROM categories ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
  $categories[] = $row['name'];
}
$topCategories = array_slice($categories, 0, 3);
$moreCategories = array_slice($categories, 3);

// Fetch transactions
$user_id = 1; // Replace with session user ID later
$stmt = $mysqli->prepare("SELECT t.id, c.name AS category, t.item, t.description, t.type AS transaction_type, t.amount, t.date, t.payment_method FROM transactions t JOIN categories c ON t.category_id = c.id WHERE t.user_id = ? ORDER BY t.date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Categories - Expense Tracker</title>
  <link rel="stylesheet" href="categories_styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
  <h2>Recent Transactions</h2>
  <input type="text" id="searchBox" placeholder="Search transactions...">
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
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <button id="open-modal">+ Add New Expense</button>
</div>

<!-- Add Expense Modal -->
<div class="modal" id="expense-modal">
  <div class="modal-box">
    <span class="close-button" id="close-modal">&times;</span>
    <h2>Add New Expense</h2>
    <form id="expense-form" action="submit-expense.php" method="POST" enctype="multipart/form-data">
      <label>Date *</label>
      <input type="date" name="expense-date" id="expense-date" max="<?= date('Y-m-d') ?>" required>

      <label>Category *</label>
      <select name="expense-category" id="expense-category" required>
        <option value="">Select Category</option>
        <?php foreach ($topCategories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
        <?php if (count($moreCategories) > 0): ?>
          <option value="ShowMore">More...</option>
        <?php endif; ?>
        <option value="AddNew">+ Add New Category</option>
      </select>

      <optgroup label="More Categories" id="more-categories-group" style="display:none;">
        <?php foreach ($moreCategories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
      </optgroup>

      <div id="new-category-div" style="display:none;">
        <label>New Category *</label>
        <input type="text" id="new-category-input">
        <input type="hidden" name="new-category" id="hidden-new-category">
      </div>

      <label>Item *</label>
      <input type="text" name="expense-item" required>

      <label>Amount *</label>
      <input type="number" name="expense-amount" required>

      <label>Transaction Type *</label>
      <select name="transaction-type" required>
        <option value="">Select</option>
        <option value="Expense">Expense</option>
        <option value="Income">Income</option>
      </select>

      <label>Payment Method *</label>
      <select name="payment-method" required>
        <option value="">Select</option>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="UPI">UPI</option>
      </select>

      <label>Attach File</label>
      <input type="file" name="expense-file" accept=".pdf,.json,.docx,.txt">

      <label>Description</label>
      <textarea name="expense-description"></textarea>

      <button type="submit">Save</button>
      <button type="button" id="cancel-modal">Cancel</button>
    </form>
  </div>
</div>

<script>
$(function () {
  $('#open-modal').click(() => $('#expense-modal').addClass('show'));
  $('#close-modal, #cancel-modal').click(() => $('#expense-modal').removeClass('show'));

  $('#expense-category').change(function () {
    const val = $(this).val();
    if (val === "AddNew") {
      $('#new-category-div').show();
    } else if (val === "ShowMore") {
      $('#more-categories-group').toggle();
      $(this).val('');
    } else {
      $('#new-category-div').hide();
      $('#hidden-new-category').val('');
    }
  });

  $('#expense-form').submit(function (e) {
    if ($('#expense-category').val() === 'AddNew') {
      const newCat = $('#new-category-input').val().trim();
      if (!newCat) {
        alert("Enter category name.");
        e.preventDefault();
        return;
      }
      $('#hidden-new-category').val(newCat);
      $.post('categories.php', { add_category_ajax: 1, name: newCat }, function (res) {
        try {
          const json = JSON.parse(res);
          if (json.success) {
            alert("Category saved. Reloading...");
            location.reload();
          } else {
            alert("Error: " + json.error);
          }
        } catch {
          alert("Server error.");
        }
      });
      e.preventDefault();
    }
  });

  $('#searchBox').on("input", function () {
    const query = $(this).val().toLowerCase();
    $('#transactionTable tbody tr').each(function () {
      const row = $(this);
      const match = row.text().toLowerCase().includes(query);
      row.toggle(match);
    });
  });
});
</script>
</body>
</html>