function paginateTable(data, perPage, tbody, paginationContainer, renderRow) {
  let currentPage = 1;

  function renderTable(page) {
    tbody.innerHTML = "";
    const start = (page - 1) * perPage; 
    const end = start + perPage;
    const pageData = data.slice(start, end);

    pageData.forEach(item => {
      const tr = renderRow(item);
      tbody.appendChild(tr);
    });
  }

  function renderPagination() {
    paginationContainer.innerHTML = "";
    const totalPages = Math.ceil(data.length / perPage);

    // Previous button
    const prevBtn = document.createElement("button");
    prevBtn.textContent = "«";
    prevBtn.className = `px-3 py-1 rounded ${
      currentPage === 1
        ? "bg-gray-200 text-gray-400 cursor-not-allowed"
        : "bg-gray-100 hover:bg-gray-200 text-gray-700"
    }`;
    prevBtn.disabled = currentPage === 1;
    prevBtn.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        renderTable(currentPage);
        renderPagination();
      }
    });
    paginationContainer.appendChild(prevBtn);

    // Số trang
    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.className = `px-3 py-1 rounded ${
        i === currentPage
          ? "bg-blue-500 text-white"
          : "bg-gray-100 hover:bg-gray-200 text-gray-700"
      }`;
      btn.addEventListener("click", () => {
        currentPage = i;
        renderTable(currentPage);
        renderPagination();
      });
      paginationContainer.appendChild(btn);
    }

    // Next button
    const nextBtn = document.createElement("button");
    nextBtn.textContent = "»";
    nextBtn.className = `px-3 py-1 rounded ${
      currentPage === totalPages
        ? "bg-gray-200 text-gray-400 cursor-not-allowed"
        : "bg-gray-100 hover:bg-gray-200 text-gray-700"
    }`;
    nextBtn.disabled = currentPage === totalPages;
    nextBtn.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        renderTable(currentPage);
        renderPagination();
      }
    });
    paginationContainer.appendChild(nextBtn);
  }

  // Render lần đầu
  renderTable(currentPage);
  renderPagination();
}


export default paginateTable;