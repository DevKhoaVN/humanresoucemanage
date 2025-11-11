import paginateTable from "../utils/render.js";
async function loadEmployees() {
  const API_URL_EMPLOYEES = "http://localhost:63342/index.php?url=home/getEmployeesDashboard";
  const API_URL_DASHBOARD = "http://localhost:63342/index.php?url=home/getInforDashboard";

  try {
    // ---fetch song song ---
    const [employeeRes, dashboardRes] = await Promise.all([
      fetch(API_URL_EMPLOYEES),
      fetch(API_URL_DASHBOARD)
    ]);

    // ---pasre sang json ---
    const [employees, dashboard] = await Promise.all([
      employeeRes.json(),
      dashboardRes.json()
    ]);


    // --- Lấy phần tử ---
    const tbody = document.getElementById("employeeTableBody");
    const pagination = document.getElementById("pagination");
    const inforDashboard = document.querySelectorAll(".info");

    // --- Display dashboard info ---
    const infor = dashboard['data'];
    const values = [
      infor.total_department,
      infor.total_employee,
      infor.total_active_employee,
      infor.total_leave
    ];

    inforDashboard.forEach((div, index) => {
      div.textContent = values[index] ?? "—";
    });

    // --- Phân trang và hiển thị bảng ---
    const perPage = 10;
    const employeeList = employees["data"] || [];

    paginateTable(
      employeeList,
      perPage,
      tbody,
      pagination,
      (emp) => {
        const tr = document.createElement("tr");
        tr.classList.add("hover:bg-gray-50", "transition");

        tr.innerHTML = `
          <td class="py-3 px-4">${emp.id}</td>
          <td class="py-3 px-4 font-medium text-gray-800">${emp.fullname}</td>
          <td class="py-3 px-4 text-gray-600">${emp.department}</td>
          <td class="py-3 px-4 text-gray-600">${emp.position || "—"}</td>
          <td class="py-3 px-4 text-left">
            <span class=" truncate px-2 py-1 rounded-full  text-xs font-semibold ${
              emp.status = 'true'
                ? "bg-green-100 text-green-700"
                : "bg-red-100 text-red-600"
            }">
              ${emp.status = 'true' ? "Đang hoạt động" : "Ngừng hoạt động"}
            </span>
          </td>
          <td class="py-3 px-4 text-center max-w-[80px] truncate text-gray-600">${emp.in || "—"}</td>
          <td class="py-3 px-4 text-center max-w-[80px] truncate text-gray-600">${emp.out || "—"}</td>
        `;
        return tr;
      }
    );

  } catch (error) {
    console.error("Lỗi khi tải dữ liệu:", error);
  }
}

 loadEmployees();
