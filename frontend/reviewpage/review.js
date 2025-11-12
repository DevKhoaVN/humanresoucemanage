import paginateTable from "../utils/render.js"; // import hàm phân trang bạn đã có

// API endpoints
const URL_API_CREATE = "https://quanlinhansu.infinityfreeapp.com/api?url=review/createReview";
const URL_API_ALL = "https://quanlinhansu.infinityfreeapp.com/api?url=review/getAllReviews";
const URL_API_EMPLOYEE_DATE = "https://quanlinhansu.infinityfreeapp.com/api?url=review/getAllReviewEmployeeIDOrDate";
const URL_API_DELETE = "https://quanlinhansu.infinityfreeapp.com/api?url=review/deleteReview";

// Elements
const tbody = document.getElementById("employeeTableBody");
const modal = document.getElementById("editModal")
const monthInput = document.getElementById("dateSelect");
const pagination = document.getElementById("pagination");
const codeInput = document.getElementById("codeEmployee");
const btnSearch = document.querySelector("button.bg-gray-500");
const btnCreateReview = document.querySelector("button.bg-green-500");
const form = document.getElementById("editFormReview")
const cancelEdit = document.getElementById("cancelEdit")
// Set default date (today)
const today = new Date();
monthInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}`;

// Event listeners
monthInput.addEventListener("change", loadReview);
btnSearch.addEventListener("click", loadReview);


btnCreateReview.addEventListener("click", () => {
  modal.classList.remove("hidden");
  modal.classList.add("flex");
});

// ✅ Load bảng chấm công
async function loadReview() {
 const date = monthInput.value; // "2025-11"
 const [year, month] = date.split("-");
  const employee_id = codeInput.value.trim();

  let res;
  try {
    // Nếu không nhập mã nhân viên → lấy tất cả
    if (!employee_id) {
      res = await fetch(URL_API_ALL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ month , year }),
        credentials: "include"
      });
    } else {
      res = await fetch(URL_API_EMPLOYEE_DATE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ date, employee_id }),
         credentials: "include"
      });
    }

    const reviews = await res.json();
    console.log(reviews);
    window.reviewsList = reviews["data"] || [];

    tbody.innerHTML = ""; 

    if (!Array.isArray(window.reviewsList) || window.reviewsList.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Không có dữ liệu</td></tr>`;
      return;
    }


    paginateTable(window.reviewsList, 10, tbody, pagination, (att) => {
      const tr = document.createElement("tr");
      tr.classList.add("hover:bg-gray-50", "transition");

      tr.innerHTML = `
        <td class="py-3 px-4">${att.id}</td>
        <td class="py-3 px-4 font-medium text-gray-800">${att.employee_name}</td>
        <td class="py-3 px-4 text-gray-600">${att.content}</td>
        <td class="py-3 px-4 text-gray-600">${att.created_at?.date ? att.created_at.date.split(" ")[0] : "—"}</td>
        <td class="py-3 px-4 text-center">
          <div class="inline-flex items-center space-x-2">
            <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${att.id}">
              <i class="fa fa-edit"></i> Sửa
            </button>
            <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${att.id}">
              <i class="fa fa-trash"></i> Xóa
            </button>
          </div>
        </td>
      `;
      return tr;
    });
  } catch (error) {
    console.error(error);
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Lỗi khi tải dữ liệu</td></tr>`;
  }
}
function handleEdit(record) {
  document.getElementById("editId").value = record.id;
  document.getElementById("editEmployeeId").value = record.employee_id;
  document.getElementById("editContent").value = record.content;

  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

async function handleDelete(id) {
  const confirmDelete = confirm("Bạn có chắc muốn xóa đánh giá này?");
  if (!confirmDelete) return;

  try {
    const res = await fetch(URL_API_DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: parseInt(id) }),
       credentials: "include"
    });

    const result = await res.json();
    if (result.code == 200) {
      alert("Xóa thành công!");
      // load lại bảng
       loadReview();
    } else {
      alert(result.message || "Không thể xóa!");
    }
  } catch (err) {
    console.error(err);
    alert("Lỗi khi xóa đánh giá!");
  }
}


tbody.addEventListener("click", (e) => {
  const editBtn = e.target.closest(".edit-btn");
  if (editBtn) {
    const record = window.reviewsList.find(a => a.id == editBtn.dataset.id);
    if (!record) return alert("Không tìm thấy bản ghi!");
    handleEdit(record);   
    return;
  }

  const deleteBtn = e.target.closest(".delete-btn");
  if (deleteBtn) {
    handleDelete(deleteBtn.dataset.id);
  }
});



cancelEdit.addEventListener("click", () => {
  modal.classList.add("hidden");
  modal.classList.remove("flex");
  // reset
  form.reset();
  document.getElementById("editId").value = "";
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value;
  const employee_id = document.getElementById("editEmployeeId").value
  const content = document.getElementById("editContent").value.trim();

  if (!employee_id) {
  alert("Vui lòng nhập mã nhân viên!");
  return;
  }

  if (!content) {
  alert("Vui lòng nhập nội dung đánh giá!");
  return;
  }
  try {
    const res = await fetch(URL_API_CREATE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({  id: id ? parseInt(id) : null, employee_id, content }),
       credentials: "include"
    });

    const result = await res.json();
    if (result.code == 200 ) {
      alert("Cập nhật thành công!");
      modal.classList.add("hidden");
      loadReview();
    } else {
      alert(result.message || "Không thể cập nhật!");
    }
  } catch (err) {
    console.error(err);
    alert("Lỗi khi cập nhật!");
  }
});


// ✅ Load bảng khi mở trang
loadReview();
