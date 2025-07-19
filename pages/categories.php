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
 <style>
  .custom-select-wrapper {
    position: relative;
    user-select: none;
  }

  .custom-select {
    border: 1px solid #ccc;
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    font-size: 14px;
  }

  .custom-options {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    border: 1px solid #ccc;
    background: white;
    max-height: 200px;
    overflow-y: auto;
    z-index: 100;
  }

  .custom-options div {
    padding: 10px;
    cursor: pointer;
    font-size: 13px;
  }

  .custom-options div:hover {
    background-color: #f0f0f0;
  }
</style>


  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="../assests/budget.png" alt="Logo">
      <p>FinTrack Pro</p>
    </div>
    <ul class="menu">
      <li><a href="../pages/dashboard.php"><i class="fas fa-chart-line"></i><span>Dashboard</span></a></li>
      <li class="active"><a href="#"><i class="fas fa-list-alt"></i><span>Expenses</span></a></li>
      <li><a href="../pages/profile.php"><i class="fas fa-user"></i><span>My Profile</span></a></li>
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
        <img src="../assests/profile.png" />
      </div>
    </div>

    <!-- Top Bar -->
    <div class="container">
      <div class="top-bar">
        <div style="display: flex; flex-direction: column;">
          <h2>Recent Expenses</h2>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
          <button class="add-expense-btn" id="open-modal">
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
    <th>Action</th> <!-- NEW -->
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

  <!-- Add Expense Modal -->
  <div class="modal" id="expense-modal">
    <div class="modal-box">
      <span class="close-button" id="close-modal">&times;</span>
      <h2>Add New Expense Details</h2>

      <form id="expense-form" action="../php/submit-expense.php" method="POST" enctype="multipart/form-data">
        <div>
          <label for="expense-date">Date <span class="required">*</span></label>
          <input type="date" id="expense-date" name="expense-date" max="" required>
        </div>
<!-- Replace the existing category block in your form with this -->
<div>
  <label for="expense-category">Category <span class="required">*</span></label>
  <div class="custom-select-wrapper">
    <div class="custom-select" id="category-display">Select Category</div>
    <div class="custom-options" id="category-options" style="display: none;">
      <div data-value="Salary">Salary</div>
      <div data-value="Freelance">Freelance</div>
      <div data-value="Business">Business</div>
      <div data-value="Utilities">Utilities</div>
      <div data-value="Medical">Medical</div>
      <div data-value="Food">Food</div>
      <div data-value="Transport">Transport</div>
      <div data-value="Rent">Rent</div>
      <div data-value="Investment">Investment</div>
      <div data-value="Entertainment">Entertainment</div>
      <div data-value="Other">Other</div>
      <div data-value="AddNew">+ Add New Category</div>
    </div>
    
    <!-- Hidden input to submit selected category -->
    <input type="hidden" id="expense-category" name="expense-category" required>
    
  </div>
</div>

<!-- Keep this unchanged -->
<!-- Hidden field for new category -->
<input type="hidden" id="hidden-new-category" name="new-category">

<!-- Shown when "Add New" is selected -->
<div id="new-category-div" style="display: none;">
  <label for="new-category-input">Enter New Category Name <span class="required">*</span></label>
  <input type="text" id="new-category-input" placeholder="e.g., Medical, Rent">
</div>

        

        <div>
          <label for="expense-item">Item <span class="required">*</span></label>
          <input type="text" id="expense-item" name="expense-item" placeholder="e.g., Pizza, Uber Ride" required>
        </div>

        <div>
          <label for="expense-amount">Amount <span class="required">*</span></label>
          <input type="number" id="expense-amount" name="expense-amount" placeholder="amount" required>
        </div>

        <div>
          <label for="transaction-type">Transaction Type <span class="required">*</span></label>
          <select id="transaction-type" name="transaction-type" required>
            <option value="">Select Type</option>
            <option value="Expense">Expense</option>
            <option value="Income">Income</option>
          </select>
        </div>

        <div>
          <label for="payment-method">Payment Method <span class="required">*</span></label>
          <select id="payment-method" name="payment-method" required>
            <option value="">Select Method</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="UPI">UPI</option>
            <option value="Credit Card">Credit Card</option>
            <option value="Debit Card">Debit Card</option>
      
            <option value="Unknown">Unknown</option>




          </select>
        </div>

        <div>
          <label for="expense-file">Attach File (PDF, JSON, DOCX, TXT, Image)</label>
          <input type="file" id="expense-file" name="expense-file" accept=".pdf,.json,.docx,.txt,.jpg,.jpeg,.png">
        </div>

        <div class="full-width">
          <label for="expense-description">Description</label>
          <textarea id="expense-description" name="expense-description" placeholder="description"></textarea>
        </div>

        <div class="form-buttons">
          <span class="cancel-button" id="cancel-modal">Cancel</span>
          <button type="submit" class="save-button">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Script -->
  <script>
    $(document).ready(function () {
      $('#open-modal').click(function () {
        $('#expense-modal').addClass('show');
      });

      $('#close-modal, #cancel-modal').click(function () {
        $('#expense-modal').removeClass('show');
      });

      const today = new Date().toISOString().split("T")[0];
      $('#expense-date').attr("max", today);

      $('#expense-category').on('change', function () {
        if ($(this).val() === 'AddNew') {
          $('#new-category-div').show();
          $('#new-category-input').attr('required', true);
        } else {
          $('#new-category-div').hide();
          $('#new-category-input').removeAttr('required');
          $('#hidden-new-category').val('');
        }
      });
      $(document).ready(function () {
    // Toggle category dropdown
    $('#category-display').click(function () {
      $('#category-options').toggle();
    });

    // Select a category
    $('#category-options div').click(function () {
      const value = $(this).data('value');
      $('#expense-category').val(value);
      $('#category-display').text($(this).text());
      $('#category-options').hide();

      if (value === 'AddNew') {
        $('#new-category-div').show();
        $('#new-category-input').attr('required', true);
      } else {
        $('#new-category-div').hide();
        $('#new-category-input').removeAttr('required');
        $('#hidden-new-category').val('');
      }
    });

    // When submitting, set hidden new-category if "AddNew"
    $('#expense-form').on('submit', function () {
      if ($('#expense-category').val() === 'AddNew') {
        $('#hidden-new-category').val($('#new-category-input').val());
      }
    });

    // Hide dropdown if clicking outside
    $(document).on('click', function (e) {
      if (!$(e.target).closest('.custom-select-wrapper').length) {
        $('#category-options').hide();
      }
    });
  });

      $('#expense-form').on('submit', function () {
        if ($('#expense-category').val() === 'AddNew') {
          $('#hidden-new-category').val($('#new-category-input').val());
        }
      });
    });

    const rowsPerPage = 10;
    let allTransactions = [], filteredData = [], currentPage = 1, sortDirection = {}, currentSortKey = "";

    function renderTableRows(data) {
      const tbody = document.querySelector("#transactionTable tbody");
      tbody.innerHTML = "";
      const pageData = data.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage);
      pageData.forEach(txn => {
       const row = `
            <tr data-id="${txn.id}">
            <td>${txn.date}</td>
            <td>${txn.category}</td>
            <td>${txn.description}</td>
            <td>${txn.item}</td>
            <td>${txn.transaction_type}</td>
            <td style="color:#3949ab;font-weight:bold;">$${parseFloat(txn.amount).toFixed(2)}</td>
             <td>${txn.payment_method}</td>
              <td>
             <a href="../php/view_receipt.php?id=${txn.id}" target="_blank" class="view-btn" title="View"><i class="fa fa-eye"></i></a>
             <a href="../php/download_receipt.php?id=${txn.id}" class="edit-btn" title="Download"><i class="fa fa-download"></i></a>
             </td>
           <td>
             <i class="fa fa-trash delete-btn" style="color:red; cursor:pointer;" title="Delete"></i>
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
      filteredData = allTransactions.filter(txn => {
        // Check if transaction object and properties exist before searching
        return (txn.date && txn.date.toString().toLowerCase().includes(query)) ||
               (txn.category && txn.category.toString().toLowerCase().includes(query)) ||
               (txn.description && txn.description.toString().toLowerCase().includes(query)) ||
               (txn.item && txn.item.toString().toLowerCase().includes(query)) ||
               (txn.transaction_type && txn.transaction_type.toString().toLowerCase().includes(query)) ||
               (txn.payment_method && txn.payment_method.toString().toLowerCase().includes(query)) ||
               (txn.amount && txn.amount.toString().toLowerCase().includes(query));
      });
      currentPage = 1;
      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
    }

    document.addEventListener("DOMContentLoaded", () => {
      fetch("../php/fetch-transactions.php")
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
    // Handle delete transaction
$(document).on("click", ".delete-btn", function () {
  const row = $(this).closest("tr");
  const id = row.data("id");

  if (confirm("Are you sure you want to delete this transaction?")) {
    $.ajax({
      url: "../php/delete-transaction.php",
      type: "POST",
      data: { id },
      success: function (res) {
        console.log("Server response:", res);  // Debug log
        try {
          const json = JSON.parse(res);
          if (json.success) {
            row.remove();
            allTransactions = allTransactions.filter(txn => txn.id !== id);
            filteredData = filteredData.filter(txn => txn.id !== id);
            renderTableRows(filteredData);
            updatePaginationControls(filteredData);
          } else {
            alert("Error: " + json.error);
          }
        } catch (e) {
          alert("Unexpected server response.");
        }
      },
      error: function () {
        alert("Failed to connect to the server.");
      }
    });
  }
});

  </script>
</body>
</html>