    import paginateTable from "../utils/render.js";
    
    async function loadEmployees() {
      const API_URL_ACCOUNT = "http://localhost:63342/index.php?url=employee/getAllEmployee";
      try {
        const res = await fetch(API_URL_ACCOUNT);
        const employees = await res.json();
    

        console.log("employee : " , employees)
        const tbody = document.getElementById("employeeTableBody");
        const pagination = document.getElementById("pagination");
    
        const perPage = 10;
        window.EmployeeList = employees["data"] || [];
    
        paginateTable(window.EmployeeList, perPage, tbody, pagination, (employee) => {
          const tr = document.createElement("tr");
          tr.classList.add("hover:bg-gray-50", "transition");
    
         
          tr.innerHTML = `
            <td class="py-3 px-4">${employee.id}</td>
            <td class="py-3 px-4 font-medium text-gray-800">${employee.fullname}</td>
            <td class="py-3 px-4 text-gray-600">${employee.email}</td>
            <td class="py-3 px-4 text-gray-600">${employee.phone}</td>
            <td class="py-3 px-4">${employee.position_name}</td>
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
            </td>
          `;
          return tr;
        });
      } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
      }
    }

  async function deleteAccount(id) {
  const API_DELETE = "http://localhost:63342/index.php?url=account/deleteAccountById";
  if (!confirm("Bạn có chắc chắn muốn xóa tài khoản này?")) return;

  try {
    const res = await fetch(API_DELETE, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id }),
    });

    const data = await res.json();
    alert(data.message || "Đã xóa thành công!");
    loadAccounts();
  } catch (error) {
    console.error("Lỗi khi xóa:", error);
  }
}

// === Xử lý nút Sửa / Xóa ===
document.addEventListener("click", (e) => {
  if (e.target.closest(".edit-btn")) {
    const id = e.target.closest(".edit-btn").dataset.id;
    openEditModal(id);
  } else if (e.target.closest(".delete-btn")) {
    const id = e.target.closest(".delete-btn").dataset.id;
    deleteAccount(id);
  }
});

// === Modal ===
function openEditModal(id) {
  const acc = window.currentAccounts.find((a) => a.id == id);
  if (!acc) return;

  document.getElementById("editId").value = acc.id;
  document.getElementById("editUsername").value = acc.username;
  document.getElementById("editRole").value = acc.role;
  document.getElementById("editPassword").value = "";

  const modal = document.getElementById("editModal");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
}

function closeEditModal() {
  const modal = document.getElementById("editModal");
  modal.classList.add("hidden");
  modal.classList.remove("flex");
}

document.getElementById("cancelEdit").addEventListener("click", closeEditModal);

document.getElementById("editForm").addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value;
  const role = document.getElementById("editRole").value;
  const password = document.getElementById("editPassword").value;

  const payload = { id, "role" : role, "passwordhash" : password };

  console.log("payload " ,payload)
  try {
    const res = await fetch("http://localhost:63342/index.php?url=account/updateAccount", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const data = await res.json();
    if (data.code == 200) {
      alert("Cập nhật thành công!");
      closeEditModal();
      loadAccounts();
    } else {
      alert("Cập nhật thất bại: " + (data.error || "Không xác định"));
    }
  } catch (err) {
    console.error("Lỗi:", err);
    alert("Có lỗi khi cập nhật tài khoản!");
  }
});
    
    
    
    loadEmployees();
    