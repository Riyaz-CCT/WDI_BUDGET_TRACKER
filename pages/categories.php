<?php require_once '../php/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Categories - Expense Tracker</title>
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
      <li><a href="dashboard.php"><i class="fas fa-chart-line"></i><span>Dashboard</span></a></li>
      <li class="active"><a href="#"><i class="fas fa-list-alt"></i><span>Expenses</span></a></li>
      <li><a href="profile.php"><i class="fas fa-user"></i><span>My Profile</span></a></li>
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
        <a href="profile.php"><img src="../assests/profile.png" alt="profile picture" /></a>
      </div>
    </div>

    <!-- Top Bar -->
    <div class="container">
      <div class="top-bar">
        <div style="display: flex; flex-direction: column;">
          <h2>Recent Expenses</h2>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <button class="add-expense-btn" onclick="openModal()">
            <span class="plus-icon">+</span> <span style="color:white">Add New Expense</span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <table id="transactionTable">
        <thead>
          <tr>
            <th data-sort="date">Date <i class="fa fa-sort"></i></th>
            <th data-sort="category">Category <i class="fa fa-sort"></i></th>
            <th>Description</th>
            <th>Item</th>
            <th>Type</th>
            <th data-sort="amount">Amount <i class="fa fa-sort"></i></th>
            <th>Payment</th>
            <th>Receipt</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination" id="pagination-controls">
        <button id="firstPageBtn">&laquo;</button>
        <button id="prevPageBtn">&lt;</button>
        <span id="pageButtons"></span>
        <button id="nextPageBtn">&gt;</button>
        <button id="lastPageBtn">&raquo;</button>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal" id="expenseModal">
    <div class="modal-box">
      <span class="close-button" onclick="closeModal()">&times;</span>
      <h2>Add New Expense Details</h2>
      <form action="../php/submit-expense.php" method="POST" enctype="multipart/form-data">
        <label for="expense-date">Date <span class="required">*</span></label>
        <input type="date" id="expense-date" name="expense-date" required max="<?= date('Y-m-d'); ?>" />

        <label for="expense-category">Category <span class="required">*</span></label>
        <select id="expense-category" name="expense-category" required>
          <option value="">Select Category</option>
          <option value="Food">Food</option>
          <option value="Travel">Travel</option>
          <option value="Grocery">Grocery</option>
          <option value="Entertainment">Entertainment</option>
          <option value="Education">Education</option>
          <option value="Others">Others</option>
        </select>
        <a href="#" class="add-category">+ Add New Category</a>

        <label for="expense-amount">Amount <span class="required">*</span></label>
        <input type="number" id="expense-amount" name="expense-amount" placeholder="amount" required />

        <label for="transaction-type">Transaction Type <span class="required">*</span></label>
        <select id="transaction-type" name="transaction-type" required>
          <option value="">Select Type</option>
          <option value="Expense">Expense</option>
          <option value="Income">Income</option>
        </select>

        <label for="payment-method">Payment Method <span class="required">*</span></label>
        <select id="payment-method" name="payment-method" required>
          <option value="">Select Method</option>
          <option value="Cash">Cash</option>
          <option value="Card">Card</option>
          <option value="UPI">UPI</option>
        </select>

        <label for="expense-file">Attach File</label>
        <input type="file" id="expense-file" name="expense-file" accept=".pdf,.json,.docx,.txt" />

        <label for="expense-description">Description</label>
        <textarea id="expense-description" name="expense-description" placeholder="description"></textarea>

        <div class="form-buttons">
          <button type="button" class="cancel-button" onclick="closeModal()">Cancel</button>
          <button type="submit" class="save-button">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    function openModal() {
      document.getElementById("expenseModal").classList.add("show");
    }

    function closeModal() {
      document.getElementById("expenseModal").classList.remove("show");
    }

    $(document).ready(function () {
      const today = new Date().toISOString().split("T")[0];
      $('#expense-date').attr("max", today);
    });

    const rowsPerPage = 10;
    let allTransactions = [], filteredData = [], currentPage = 1, sortDirection = {}, currentSortKey = "";

    function renderTableRows(data) {
      const tbody = document.querySelector("#transactionTable tbody");
      tbody.innerHTML = "";
      const pageData = data.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage);
      pageData.forEach(txn => {
        const row = `
          <tr>
            <td>${txn.date}</td>
            <td>${txn.category}</td>
            <td>${txn.description}</td>
            <td>${txn.item}</td>
            <td>${txn.transaction_type}</td>
            <td style="color:#3949ab;font-weight:bold;">$${txn.amount.toFixed(2)}</td>
            <td>${txn.payment_method}</td>
            <td>
              <button class="view-btn"><i class="fa fa-eye"></i></button>
              <button class="edit-btn"><i class="fa fa-download"></i></button>
            </td>
          </tr>`;
        tbody.insertAdjacentHTML("beforeend", row);
      });
    }

    function updatePaginationControls(data) {
      const totalPages = Math.ceil(data.length / rowsPerPage);
      const container = document.getElementById("pageButtons");
      container.innerHTML = "";
      for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        if (i === currentPage) btn.classList.add("active");
        btn.onclick = () => {
          currentPage = i;
          renderTableRows(filteredData);
          updatePaginationControls(filteredData);
        };
        container.appendChild(btn);
      }

      document.getElementById("firstPageBtn").onclick = () => { currentPage = 1; renderTableRows(filteredData); updatePaginationControls(filteredData); };
      document.getElementById("prevPageBtn").onclick = () => { if (currentPage > 1) currentPage--; renderTableRows(filteredData); updatePaginationControls(filteredData); };
      document.getElementById("nextPageBtn").onclick = () => { if (currentPage < totalPages) currentPage++; renderTableRows(filteredData); updatePaginationControls(filteredData); };
      document.getElementById("lastPageBtn").onclick = () => { currentPage = totalPages; renderTableRows(filteredData); updatePaginationControls(filteredData); };
    }

    function sortData(key) {
      if (currentSortKey === key) sortDirection[key] = !sortDirection[key];
      else { sortDirection = {}; sortDirection[key] = true; currentSortKey = key; }

      filteredData.sort((a, b) => {
        const valA = a[key], valB = b[key];
        if (key === "date") return sortDirection[key] ? new Date(valA) - new Date(valB) : new Date(valB) - new Date(valA);
        if (typeof valA === "number") return sortDirection[key] ? valA - valB : valB - valA;
        return sortDirection[key] ? valA.localeCompare(valB) : valB.localeCompare(valA);
      });

      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
      updateSortIcons(key);
    }

    function updateSortIcons(activeKey) {
      document.querySelectorAll("#transactionTable thead th").forEach(th => {
        const icon = th.querySelector("i");
        const key = th.dataset.sort;
        if (!key || !icon) return;
        icon.className = "fa fa-sort";
        if (key === activeKey) {
          icon.className = sortDirection[key] ? "fa fa-sort-up" : "fa fa-sort-down";
        }
      });
    }

    function applySearch(query) {
      filteredData = allTransactions.filter(txn =>
        txn.date.includes(query) ||
        txn.category.toLowerCase().includes(query) ||
        txn.description.toLowerCase().includes(query) ||
        txn.item.toLowerCase().includes(query) ||
        txn.transaction_type.toLowerCase().includes(query) ||
        txn.payment_method.toLowerCase().includes(query)
      );
      currentPage = 1;
      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
    }

    document.addEventListener("DOMContentLoaded", () => {
      fetch("transaction_data.json")
        .then(res => res.json())
        .then(json => {
          allTransactions = json.recent_transactions;
          filteredData = [...allTransactions];
          renderTableRows(filteredData);
          updatePaginationControls(filteredData);
        });

      document.getElementById("searchBox").addEventListener("input", e => {
        applySearch(e.target.value.toLowerCase());
      });

      document.querySelectorAll("#transactionTable thead th[data-sort]").forEach(th => {
        th.addEventListener("click", () => {
          sortData(th.dataset.sort);
        });
      });
    });
  </script>
</body>
</html>
