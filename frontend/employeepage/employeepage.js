import paginateTable from "../utils/render.js";
const editModal = document.getElementById("editModal");
const cancelEdit = document.getElementById("cancelEdit");
const editForm = document.getElementById("editForm");
const tbody = document.getElementById("employeeTableBody");
const searchBtn = document.getElementById("btnSearchEmployee")
const codeInput = document.getElementById("searchEmployee")
async function loadEmployees() {
    const API_URL_ACCOUNT = "https://quanlinhansu.infinityfreeapp.com/api?url=employee/getAllEmployee";
    try {
      const res = await fetch(API_URL_ACCOUNT);
       const employees = await res.json();
    

      console.log("employee : " , employees)
      const tbody = document.getElementById("employeeTableBody");
      const pagination = document.getElementById("pagination");
    
       const perPage = 10;
       window.EmployeeList = employees["data"].filter(e => e.is_active == 1) || [];

      paginateTable(window.EmployeeList, perPage, tbody, pagination, (employee) => {
         const tr = document.createElement("tr");
        tr.classList.add("hover:bg-gray-50", "transition");
    
         
        tr.innerHTML = `
          <td class="py-3 px-4">${employee.id}</td>
           <td class="py-3 px-4 font-medium text-gray-800">${employee.fullname}</td>
          <td class="py-3 px-4 text-gray-600">${employee.email}</td>
          <td class="py-3 px-4 text-gray-600">${employee.phone}</td>
          <td class="py-3 px-4">${employee.position_name}</td>
          <td class="py-3 px-4">${employee.department_name}</td>
          <td class="py-3 px-4">${employee.address}</td>
          <td class="py-3 px-4 text-center">
             <div class="inline-flex items-center space-x-2">
              <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${employee.id}">
                <i class="fa fa-edit"></i> Sửa
              </button>
              <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${employee.id}">
                 <i class="fa fa-trash"></i> Xóa
              </button>
             </div>
          `;
          return tr;
        });
     } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
    }
}

searchBtn.addEventListener("click" ,async (e) => {
  e.preventDefault();

  const codeEmployee = codeInput.value.trim();
  if(!codeEmployee) {
    alert("Vui long nhan nhập dữ liệu")
    return
  }

  let API = "";
  let payload = {}
  if (/^\d+$/.test(codeEmployee)) {
  API = "https://quanlinhansu.infinityfreeapp.com/api?url=employee/getEmployeeById";
  payload.id = parseInt(codeEmployee);
 } else {
  API = "https://quanlinhansu.infinityfreeapp.com/api?url=employee/searchEmployee";
  payload.searchTemp = codeEmployee;
  }

  const res = await fetch(API , {
    method: "POST",
    headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
  })

  const employees = await res.json();
  const tbody = document.getElementById("employeeTableBody");
  const pagination = document.getElementById("pagination");
    
  const perPage = 10;
 let data = employees["data"];
 let EmployeeList = [];

  if (Array.isArray(data)) {
    EmployeeList = data.filter(e => e.is_active == 1);
  } else if (data) {
  
    if (data.is_active == 1) {
        EmployeeList.push(data);
    }
}
window.EmployeeList = EmployeeList;

  paginateTable(window.EmployeeList, perPage, tbody, pagination, (employee) => {
  const tr = document.createElement("tr");
  tr.classList.add("hover:bg-gray-50", "transition");
    
         
  tr.innerHTML = `
  <td class="py-3 px-4">${employee.id}</td>
  <td class="py-3 px-4 font-medium text-gray-800">${employee.fullname}</td>
  <td class="py-3 px-4 text-gray-600">${employee.email}</td>
  <td class="py-3 px-4 text-gray-600">${employee.phone}</td>
  <td class="py-3 px-4">${employee.position_name}</td>
  <td class="py-3 px-4">${employee.department_name}</td>
  <td class="py-3 px-4">${employee.address}</td>
  <td class="py-3 px-4 text-center">
    <div class="inline-flex items-center space-x-2">
    <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${employee.id}">
      <i class="fa fa-edit"></i> Sửa
     </button>
     <button class="delete-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${employee.id}">
        <i class="fa fa-trash"></i> Xóa
    </button>
    </div>
  `;
  return tr;
});
})
async function handleEdit(id) {
  const employee = window.EmployeeList.find(emp => emp.id == id);
  if (!employee) return alert("Không tìm thấy nhân viên!");

  await loadDepartmentsAndPositions();
  // Điền dữ liệu vào form
  document.getElementById("editId").value = employee.id;
  document.getElementById("editFullName").value = employee.fullname;
  document.getElementById("editEmail").value = employee.email;
  document.getElementById("editPhone").value = employee.phone;
  document.getElementById("editAddress").value = employee.address;
  

  editModal.classList.remove("hidden");
  editModal.classList.add("flex");
}
editForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value.trim();
  const fullname = document.getElementById("editFullName").value.trim();
  const email = document.getElementById("editEmail").value.trim();
  const phone = document.getElementById("editPhone").value.trim();
  const address = document.getElementById("editAddress").value.trim();
  const position_id = document.getElementById("positionSelect").value;
  const department_id = document.getElementById("departmentSelect").value;

  if (!fullname) return alert("Tên nhân viên không được để trống!");
  if (!email) return alert("Email không được để trống!");

  const payload = {
    id: parseInt(id),
    fullname,
    email,
    phone,
    address,
    position_id: position_id || null,
    department_id: department_id || null
  };
  console.log("pyload : " , payload);

  try {
    const API_UPDATE = "https://quanlinhansu.infinityfreeapp.com/api?url=employee/createEmployee";
    const res = await fetch(API_UPDATE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
      credentials: "include"
    });
    const data = await res.json();
    if (data.code == 200) {
      alert(data.message || "Cập nhật thành công!");
      editModal.classList.add("hidden");
      editForm.reset();
      await loadEmployees();
    } else {
      alert(data.message || "Cập nhật thất bại!");
    }
  } catch (error) {
    console.error("Lỗi khi cập nhật nhân viên:", error);
    alert("Không thể lưu dữ liệu!");
  }
});

tbody.addEventListener("click", (e) => {
  const btn = e.target.closest("button");
  if (!btn) return;

  const id = btn.dataset.id;

  if (btn.classList.contains("edit-btn")) {
    handleEdit(id);
  } else if (btn.classList.contains("delete-btn")) {
    handleDelete(id);
  }
});
cancelEdit.addEventListener("click", () => {
  editModal.classList.add("hidden");
  editModal.classList.remove("flex");
  editForm.reset();
});

async function handleDelete(id) {
  const API_DELETE = "https://quanlinhansu.infinityfreeapp.com/api?url=employee/deleteEmployee";
  if (!confirm("Bạn có chắc chắn muốn xóa nhân viên này?")) return;

  try {
    const res = await fetch(API_DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: parseInt(id) }),
      credentials: "include"
    });
    const data = await res.json();
    if(data.code == 200){
     alert(data.message || "Đã xóa thành công!");
     await loadEmployees();
    }
 
  } catch (error) {
    console.error("Lỗi khi xóa nhân viên:", error);
  }
}

async function loadDepartmentsAndPositions() {
  const API_DEPARTMENTS = "https://quanlinhansu.infinityfreeapp.com/api?url=department/getAllDepartments";
  const API_POSITIONS = "https://quanlinhansu.infinityfreeapp.com/api?url=position/getAllPositions";

  try {
    const [resDepartments, resPositions] = await Promise.all([
    fetch(API_DEPARTMENTS, { credentials: "include" }),
    fetch(API_POSITIONS, { credentials: "include" })
    ]);

    const [departmentsData, positionsData] = await Promise.all([
      resDepartments.json(),
      resPositions.json()
    ]);

    window.departmentList = departmentsData.data || [];
    window.positionList = positionsData.data || [];

    const departmentSelect = document.getElementById("departmentSelect");
    const positionSelect = document.getElementById("positionSelect");

    // Điền select bộ phận
    departmentSelect.innerHTML = "<option value=''>-- Chọn bộ phận --</option>";
    window.departmentList.forEach(dep => {
      const opt = document.createElement("option");
      opt.value = dep.id;
      opt.textContent = dep.name;
      departmentSelect.appendChild(opt);
    });

    // Khi chọn bộ phận, lọc vị trí tương ứng
    departmentSelect.addEventListener("change", () => {
      const depId = departmentSelect.value;
      const filteredPositions = window.positionList.filter(pos => pos.department_id == depId);

      positionSelect.innerHTML = "<option value=''>-- Chọn vị trí --</option>";
      filteredPositions.forEach(pos => {
        const opt = document.createElement("option");
        opt.value = pos.id;
        opt.textContent = pos.name;
        positionSelect.appendChild(opt);
      });
    });

  } catch (error) {
    console.error("Lỗi khi tải bộ phận và vị trí:", error);
  }
}



loadEmployees();
    