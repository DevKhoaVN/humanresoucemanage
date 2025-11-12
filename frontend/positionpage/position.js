import paginateTable from "../utils/render.js";

const modal = document.getElementById("addModal");
const cancelBtn = document.getElementById("cancelBtn");
const form = document.getElementById("addDepartmentForm");
const tbody = document.getElementById("employeeTableBody"); 
const addBtn = document.getElementById("addPositiontBtn");
const pagination = document.getElementById("pagination");

// API endpoints
const API_URL_POSITION = "https://quanlinhansu.infinityfreeapp.com/api?url=position/getAllPositions"; 
const API_URL_DEPARTMENT = "https://quanlinhansu.infinityfreeapp.com/api?url=department/getAllDepartments";
const API_URL_CREATE = "https://quanlinhansu.infinityfreeapp.com/api?url=position/createPostion";
const API_URL_DELETE = "https://quanlinhansu.infinityfreeapp.com/api?url=position/deletePostion";


async function loadDepartments() {
  try {
    const [resPosition, resDepartment] = await Promise.all([
      fetch(API_URL_POSITION,{credentials: "include"}),
      fetch(API_URL_DEPARTMENT,{credentials: "include"})
    ]);

    const [positions, departments] = await Promise.all([
      resPosition.json(),
      resDepartment.json()
    ]);

    window.positionList = positions.data || [];
    window.departmentList = departments.data || [];

    renderTable(window.positionList);
  } catch (error) {
    console.error("Lỗi khi tải dữ liệu:", error);
  }
}


function renderTable(list) {
  const perPage = 10;
  tbody.innerHTML = "";
  const paginationEl = pagination;

  paginateTable(list, perPage, tbody, paginationEl, (position) => {
    const tr = document.createElement("tr");
    tr.classList.add("hover:bg-gray-50", "transition");

    tr.innerHTML = `
      <td class="py-3 px-4">${position.id}</td>
      <td class="py-3 px-4 font-medium text-gray-800">${position.name}</td>
      <td class="py-3 px-4 text-gray-600">${position.description || ""}</td>
      <td class="py-3 px-4 text-gray-600">${Number(position.salary || 0).toLocaleString('vi-VN', { maximumFractionDigits: 0 })}₫</td>
      <td class="py-3 px-4 text-gray-600">${position.department_name}</td>
      <td class="py-3 px-4 text-center">
        <div class="inline-flex items-center space-x-2">
          <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${position.id}">
            <i class="fa fa-edit"></i> Sửa
          </button>
          <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${position.id}">
            <i class="fa fa-trash"></i> Xóa
          </button>
        </div>
      </td>
    `;
    return tr;
  });
}

addBtn.addEventListener("click", () => {
  form.reset();
  document.getElementById("editId").value = "";
  renderDepartmentOptions();
  modal.classList.remove("hidden");
  modal.classList.add("flex");
});

function renderDepartmentOptions(selectedId = null) {
  const select = document.getElementById("departmentSelect");
  select.innerHTML = "";

  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "-- Chọn bộ phận --";
  select.appendChild(defaultOption);

  window.departmentList.forEach(dep => {
    const option = document.createElement("option");
    option.value = dep.id;
    option.textContent = dep.name;
    if (selectedId && dep.id == selectedId) option.selected = true;
    select.appendChild(option);
  });
}

function handleEdit(id) {
  const position = window.positionList.find(p => p.id == id);
  if (!position) return alert("Không tìm thấy bản ghi!");

  document.getElementById("editId").value = position.id;
  document.getElementById("departmentName").value = position.name;
  document.getElementById("departmentDescription").value = position.description || "";
  document.getElementById("departmentBaseSalary").value = Number(position.salary || 0);
  renderDepartmentOptions(position.department_id);

  modal.classList.remove("hidden");
  modal.classList.add("flex");
}


async function handleDelete(id) {
  if (!confirm("Bạn có chắc chắn muốn xóa vị trí này?")) return;

  try {
    const res = await fetch(API_URL_DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: parseInt(id) }),
      credentials: "include"
    });
    const data = await res.json();
    alert(data.message || "Đã xóa thành công!");
    loadDepartments();
  } catch (error) {
    console.error("Lỗi khi xóa:", error);
  }
}


tbody.addEventListener("click", (e) => {
  const btn = e.target.closest("button");
  if (!btn) return;
  const id = btn.dataset.id;

  if (btn.classList.contains("edit-btn")) handleEdit(id);
  else if (btn.classList.contains("delete-btn")) handleDelete(id);
});


cancelBtn.addEventListener("click", () => {
  modal.classList.add("hidden");
  modal.classList.remove("flex");
  form.reset();
  document.getElementById("editId").value = "";
});


form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value.trim();
  const name = document.getElementById("departmentName").value.trim();
  const description = document.getElementById("departmentDescription").value.trim();
  const base_salary = document.getElementById("departmentBaseSalary").value.trim();
  const department_id = document.getElementById("departmentSelect").value;

  if (!name) return alert("Tên vị trí không được để trống!");
  if (!department_id) return alert("Vui lòng chọn bộ phận!");

  const salary = Number(base_salary);
  if (isNaN(salary) || salary < 0) return alert("Lương cơ bản phải là số hợp lệ >= 0");

  const payload = { name, description, base_salary: salary, department_id };
  if (id) payload.id = parseInt(id);

  try {
    const res = await fetch(API_URL_CREATE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
      credentials: "include"
    });

    const data = await res.json();
    if (data.code === 200) {
      alert(data.message || (id ? "Cập nhật thành công!" : "Thêm mới thành công!"));
      modal.classList.add("hidden");
      form.reset();
      await loadDepartments();
    } else {
      alert(data.message || "Có lỗi xảy ra!");
    }
  } catch (error) {
    console.error("Lỗi khi lưu dữ liệu:", error);
    alert("Không thể lưu dữ liệu!");
  }
});


loadDepartments();
