// ================= Expense Tracker Logic =================

const rowsPerPage = 10;
let allTransactions = [];
let filteredData = [];
let currentPage = 1;

// Function to render table rows based on current data and page
function renderTableRows(data) {
  const tbody = $("#transactionTable tbody");
  tbody.empty();

  const paginatedData = data.slice((currentPage - 1) * rowsPerPage, currentPage * rowsPerPage);
  paginatedData.forEach(txn => {
    const row = `
      <tr>
        <td>${txn.date}</td>
        <td>${txn.category}</td>
        <td>${txn.description}</td>
        <td>${txn.item}</td>
        <td>${txn.transaction_type}</td>
        <td>$${txn.amount.toFixed(2)}</td>
        <td>${txn.payment_method}</td>
        <td>
          <button class="view-btn" title="View Receipt">
            <i class="fa fa-eye"></i>
          </button>
          <button class="download-btn" title="Download Receipt">
            <i class="fa fa-download"></i>
          </button>
        </td>
      </tr>`;
    tbody.append(row);
  });
}

// Updated pagination controls with nav buttons and dynamic page buttons
function updatePaginationControls(data) {
  const totalPages = Math.ceil(data.length / rowsPerPage);
  const pageButtonsContainer = document.getElementById("pageButtons");

  pageButtonsContainer.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;
    if (i === currentPage) btn.classList.add("active");
    btn.addEventListener("click", () => {
      currentPage = i;
      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
    });
    pageButtonsContainer.appendChild(btn);
  }

  // Navigation button handlers
  document.getElementById("firstPageBtn").onclick = () => {
    currentPage = 1;
    renderTableRows(filteredData);
    updatePaginationControls(filteredData);
  };

  document.getElementById("prevPageBtn").onclick = () => {
    if (currentPage > 1) {
      currentPage--;
      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
    }
  };

  document.getElementById("nextPageBtn").onclick = () => {
    if (currentPage < totalPages) {
      currentPage++;
      renderTableRows(filteredData);
      updatePaginationControls(filteredData);
    }
  };

  document.getElementById("lastPageBtn").onclick = () => {
    currentPage = totalPages;
    renderTableRows(filteredData);
    updatePaginationControls(filteredData);
  };
}

// Function to filter transactions based on search query
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

// Initialization on page ready
$(document).ready(function () {
  // Load transaction data from JSON file
  $.getJSON("transaction_data.json", function (json) {
    allTransactions = json.recent_transactions;
    filteredData = [...allTransactions];
    renderTableRows(filteredData);
    updatePaginationControls(filteredData);
  });

  // Search functionality
  $("#searchBox").on("input", function () {
    const query = $(this).val().toLowerCase();
    applySearch(query);
  });
});
