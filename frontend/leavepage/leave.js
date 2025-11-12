import paginateTable from "../utils/render.js";

const dateInputBtn = document.getElementById("dateSelect");
const approvedBtn = document.getElementById("accountTableBody");
// Set mặc định là tháng hiện tại
const today = new Date();
const defaultMonth = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-${String(today.getDate() + 1).padStart(2, "0")}`;
dateInputBtn.value = defaultMonth;

// Gọi loadLeaves khi thay đổi hoặc khi mở trang
dateInputBtn.addEventListener("change", loadLeaves);

async function loadLeaves() {
  const API_URL_ALL = "https://quanlinhansu.infinityfreeapp.com/api?url=leaves/getAllLeaves";
  const date = dateInputBtn.value; // "YYYY-MM--DD"
  
  try {
    const res = await fetch(API_URL_ALL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ date }),
      credentials: "include"
    });
    const leaves = await res.json();

    const tbody = document.getElementById("accountTableBody");
    const pagination = document.getElementById("pagination");
    const perPage = 10;
    window.leavesList = leaves["data"] || [];

    paginateTable(window.leavesList, perPage, tbody, pagination, (acc) => {
      const tr = document.createElement("tr");
      tr.classList.add("hover:bg-gray-50", "transition");

      const isApproved = acc.status == 1; 
      const statusText = isApproved ? "Đã duyệt" : "Chưa duyệt";
      const statusColor = isApproved ? "text-green-600" : "text-red-600";

      tr.innerHTML = `
        <td class="py-3 px-4">${acc.id}</td>
        <td class="py-3 px-4 font-medium text-gray-800">${acc.employee_name}</td>
        <td class="py-3 px-4 text-gray-600">${acc.employee_position}</td>
        <td class="py-3 px-4 text-gray-600">${acc.reason || "—"}</td>
        <td class="py-3 px-4 ${statusColor} font-semibold">${statusText}</td>
        <td class="py-3 px-4 text-gray-600">${acc.leave_date || "—"}</td>
        <td class="py-3 px-4 text-center">
          <div class="inline-flex items-center space-x-2">
            ${!isApproved ? `
              <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${acc.id}">
                <i class="fa fa-edit"></i> Duyệt
              </button>
            ` : ""}
          </div>
        </td>
      `;
      return tr;
    });
  } catch (error) {
    console.error("Lỗi khi tải dữ liệu:", error);
  }
}


approvedBtn.addEventListener("click", async (e) => {
  const approveBtn = e.target.closest(".edit-btn"); // nút Duyệt
  if (!approveBtn) return;

  const id = approveBtn.dataset.id;
  if (!id) return;

  const confirmApprove = confirm("Bạn có chắc muốn duyệt đơn này?");
  if (!confirmApprove) return;

  try {
    const API_URL_APPROVE = "https://quanlinhansu.infinityfreeapp.com/api?url=leaves/createLeaves";
    const res = await fetch(API_URL_APPROVE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: parseInt(id) , status: 1}),
      credentials: "include"
    });

    const result = await res.json();
    if (result.code == 200) {
      alert("Duyệt thành công!");
      loadLeaves();

    } else {
      alert(result.message || "Không thể duyệt!");
    }

  } catch (err) {
    console.error(err);
    alert("Lỗi khi duyệt đơn!");
  }
});


// Load mặc định khi mở trang
loadLeaves();
