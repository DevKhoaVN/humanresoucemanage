import paginateTable from "../utils/render.js";
const addBtn = document.getElementById("addDepartmentBtn");
const modal = document.getElementById("addModal");
const cancelBtn = document.getElementById("cancelBtn");
const form = document.getElementById("addDepartmentForm");
const tbody = document.getElementById("employeeTableBody"); 
const pagination = document.getElementById("pagination");

async function loadDepartments() {
  const API_URL_DEPARTMENT = "http://localhost:63342/index.php?url=department/getAllDepartments"; 
  try {
    const res = await fetch(API_URL_DEPARTMENT);
    const departments = await res.json();

    console.log("departments:", departments);


    const perPage = 10;
    window.DepartmentList = departments["data"] || [];

    paginateTable(window.DepartmentList, perPage, tbody, pagination, (department) => {
      const tr = document.createElement("tr");
      tr.classList.add("hover:bg-gray-50", "transition");

      tr.innerHTML = `
        <td class="py-3 px-4">${department.id}</td>
        <td class="py-3 px-4 font-medium text-gray-800">${department.name}</td>
        <td class="py-3 px-4 text-gray-600">${department.description || ""}</td>
        <td class="py-3 px-4 text-gray-600">${department.status ? "Hoạt động" : "Ngưng hoạt động"}</td>
        <td class="py-3 px-4 text-center">
          <div class="inline-flex items-center space-x-2">
            <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${department.id}">
              <i class="fa fa-edit"></i> Sửa
            </button>
            <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${department.id}">
              <i class="fa fa-trash"></i> Xóa
            </button>
          </div>
        </td>
      `;
      return tr;
    });


  } catch (error) {
    console.error("Lỗi khi tải dữ liệu bộ phận:", error);
  }
}


tbody.addEventListener("click", (e) => {
  const target = e.target.closest("button"); 
  if (!target) return;

  const id = target.dataset.id;
  if (target.classList.contains("delete-btn")) {
    deleteDepartment(id);
  } else if (target.classList.contains("edit-btn")) {
    editDepartment(id);
  }
});
// Hàm xóa bộ phận
async function deleteDepartment(id) {
  const API_DELETE = "http://localhost:63342/index.php?url=department/deleteDepartment";
  if (!confirm("Bạn có chắc chắn muốn xóa bộ phận này?")) return;

  try {
    const res = await fetch(API_DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    const data = await res.json();
    alert(data.message || "Đã xóa thành công!");
    loadDepartments();
  } catch (error) {
    console.error("Lỗi khi xóa bộ phận:", error);
  }
}

// Hàm sửa bộ phận (ví dụ)
function editDepartment(id) {
 const acc = window.DepartmentList.find((a) => a.id == id);
  if (!acc) return;

  document.getElementById("titleForm").textContent = "Chỉnh sửa phòng ban"
  document.getElementById("editId").value = acc.id;
  document.getElementById("departmentDescription").value = acc.description;
  document.getElementById("departmentName").value = acc.name;
  document.getElementById("departmentStatus").value = "true";

  modal.classList.remove("hidden");
  modal.classList.add("flex");
}
// Hiển thị modal
addBtn.addEventListener("click", () => {
  modal.classList.remove("hidden");   
  modal.classList.add("flex");        
});
cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const name = document.getElementById("departmentName").value.trim();
  const description = document.getElementById("departmentDescription").value.trim();
  const status = document.getElementById("departmentStatus").checked ? 1 : 0;

  if (!name) return alert("Tên bộ phận không được để trống!");

  try {
    const API_DEPARTMENT_UPDATDE = "http://localhost:63342/index.php?url=department/updateDepartment";
    const res = await fetch(API_DEPARTMENT_UPDATDE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ name, description, status })
    });

    const data = await res.json();
    
    data.code == 200 ? alert(data.message || "Thêm bộ phận thành công!") : "";

    modal.classList.add("hidden");
    form.reset();
    loadDepartments(); // reload table
  } catch (error) {
    console.error("Lỗi khi thêm bộ phận:", error);
    alert("Thêm bộ phận thất bại!");
  }
});

// Load lần đầu
loadDepartments();
