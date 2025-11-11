import paginateTable from "../utils/render.js";
const modal = document.getElementById("addModal");
const cancelBtn = document.getElementById("cancelBtn");
const form = document.getElementById("addDepartmentForm");
const tbody = document.getElementById("employeeTableBody"); 
const addBtn = document.getElementById("addPositiontBtn");
const pagination = document.getElementById("pagination");

async function loadDepartments() {
  const API_URL_POSITION = "http://localhost:63342/index.php?url=position/getAllPositions"; 
  const API_URL_DEPARTMENT = "http://localhost:63342/index.php?url=department/getAllDepartments";
  try {
    const [resPosition, resDepartment] = await Promise.all([
    fetch(API_URL_POSITION),
    fetch(API_URL_DEPARTMENT)
  ]);


   const [positions, departments] = await Promise.all([
    resPosition.json(),
    resDepartment.json()
  ]);

  console.log("positions:", positions);
  console.log("departments:", departments);


    const perPage = 10;
    window.departmentList = departments["data"] || [];
    window.positionList = positions["data"] || [];


    paginateTable(window.positionList, perPage, tbody, pagination, (position) => {
      const tr = document.createElement("tr");
      tr.classList.add("hover:bg-gray-50", "transition");

      tr.innerHTML = `
        <td class="py-3 px-4">${position.id}</td>
        <td class="py-3 px-4 font-medium text-gray-800">${position.name}</td>
        <td class="py-3 px-4 text-gray-600">${position.description || ""}</td>
        <td class="py-3 px-4 text-gray-600">  ${Number(position.salary || 0).toLocaleString('vi-VN', { maximumFractionDigits: 0 })}₫</td>
        <td class="py-3 px-4 text-gray-600"> ${position.department_name}</td>
        <td class="py-3 px-4 text-center">
          <div class="inline-flex items-center space-x-2">
            <button class="add-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${position.id}">
              <i class="fa fa-edit"></i> sửa
            </button>
            <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${position.id}">
              <i class="fa fa-trash"></i> Xóa
            </button>
          </div>
        </td>
      `;
      return tr;
    });

     const addBtn = document.getElementById("add-btn");
if (addBtn) {
  addBtn.addEventListener("click", () => {
    modal.classList.remove("hidden");   
    modal.classList.add("flex");        
  });
}

  } catch (error) {
    console.error("Lỗi khi tải dữ liệu bộ phận:", error);
  }
}

addBtn.addEventListener("click", () => {
  const acc = window.positionList
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

    if (dep.id == acc.department_id) {
      option.selected = true;
    }

    select.appendChild(option);
  });

  modal.classList.remove("hidden");
  modal.classList.add("flex");

});


tbody.addEventListener("click", (e) => {
  const target = e.target.closest("button"); 
  if (!target) return;

  const id = target.dataset.id;
  if (target.classList.contains("delete-btn")) {
    deletePosition(id);
  } else if (target.classList.contains("add-btn")) {
    createPosition(id);
  }
});

// Hàm xóa bộ phận
async function deletePosition(id){
  const API_DELETE = "http://localhost:63342/index.php?url=position/deletePostion";
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
function createPosition(id) {
  const acc = window.positionList.find((a) => a.id == id);
  if (!acc) return;

  // Gán dữ liệu vào input
  document.getElementById("editId").value = acc.id;
  document.getElementById("departmentDescription").value = acc.description;
  document.getElementById("departmentName").value = acc.name;
  document.getElementById("departmentBaseSalary").value = Number(acc.salary || 0).toLocaleString('vi-VN', { maximumFractionDigits: 0 });

  const select = document.getElementById("departmentSelect");
  select.innerHTML = ""; // clear option cũ

  // ✅ Thêm option mặc định
  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "-- Chọn bộ phận --";
  select.appendChild(defaultOption);


  window.departmentList.forEach(dep => {
    const option = document.createElement("option");
    option.value = dep.id;
    option.textContent = dep.name;

    if (dep.id == acc.department_id) {
      option.selected = true;
    }

    select.appendChild(option);
  });

  modal.classList.remove("hidden");
  modal.classList.add("flex");
}


cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value.trim(); 
  const department_id = document.getElementById("departmentSelect").value;
  const name = document.getElementById("departmentName").value.trim();
  const description = document.getElementById("departmentDescription").value.trim();
  const base_salary = document.getElementById("departmentBaseSalary").value.trim();

  if (!name) return alert("Tên vị trí không được để trống!");
  if (!department_id) return alert("Vui lòng chọn bộ phận!");
   
   const salary = Number(base_salary);
  if (isNaN(salary) || salary < 0) {
  return alert("Lương cơ bản phải là số hợp lệ lớn hơn hoặc bằng 0");
  }

 

  const payload = {
        name,
        description,
        base_salary: salaryValue,
        department_id
       }

   id == '' ? payload : payload.id = id

  try {
    //
    const API_URL = "http://localhost:63342/index.php?url=position/createPostion";

    const res = await fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const data = await res.json();

    if (data.code === 200) {
      alert(data.message || (id ? "Cập nhật thành công!" : "Thêm mới thành công!"));
      modal.classList.add("hidden");
      form.reset();
      await loadDepartments(); // reload lại bảng
    } else {
      alert(data.message || "Có lỗi xảy ra!");
    }
  } catch (error) {
    console.error("Lỗi khi lưu chức vụ:", error);
    alert("Không thể lưu dữ liệu!");
  }
});


// Load lần đầu
loadDepartments();
