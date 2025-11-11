import paginateTable from "../utils/render.js"; // import hàm phân trang bạn đã có

// API endpoints
const API_CHECK_OUT = "http://localhost:63342/index.php?url=attendance/checkOut";
const API_CHECK_IN = "http://localhost:63342/index.php?url=attendance/checkIn";
const API_FIND_CODE_DATE = "http://localhost:63342/index.php?url=attendance/findAttendanceById";
const API_FIND_DATE = "http://localhost:63342/index.php?url=attendance/getAllAttendances";

// Elements
const tbody = document.getElementById("employeeTableBody");
const modal = document.getElementById("editModal")
const monthInput = document.getElementById("dateSelect");
const pagination = document.getElementById("pagination");
const codeInput = document.getElementById("codeEmployee");
const btnSearch = document.querySelector("button.bg-gray-500");
const btnCheckIn = document.querySelector("button.bg-green-500");
const btnCheckOut = document.querySelector("button.bg-blue-500");
const form = document.getElementById("editFormAttendance")
const cancelEdit = document.getElementById("cancelEdit")
// Set default date (today)
const today = new Date();
monthInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-${String(today.getDate()).padStart(2, "0")}`;

// Event listeners
monthInput.addEventListener("change", loadAttendance);
btnSearch.addEventListener("click", loadAttendance);
btnCheckIn.addEventListener("click", () => handleCheck("check_in"));
btnCheckOut.addEventListener("click", () => handleCheck("check_out"));

// ✅ Thực hiện check in / check out
async function handleCheck(type) {
  const employee_id = codeInput.value.trim();
  if (!employee_id) return alert("Vui lòng nhập mã nhân viên!");

  const check_in = formatDateTime();
  const check_out = formatDateTime();


  let URL_API = null;
  let payload = { employee_id };
  if (type === "check_in") {
    payload.check_in = check_in;
    URL_API = API_CHECK_IN
  } else {
    payload.check_out = check_out;
    payload.work_date = monthInput.value
    URL_API = API_CHECK_OUT
  }

  try {
    const res = await fetch(URL_API, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    });

    const result = await res.json();
    if (result.success) {
      alert(`${type === "check_in" ? "Check in" : "Check out"} thành công!`);
      loadAttendance();
    } else {
      alert(result.message || "Có lỗi xảy ra");
    }
  } catch (error) {
    console.error(error);
    alert("Lỗi khi check in/out");
  }
}

// ✅ Load bảng chấm công
async function loadAttendance() {
  const work_date = monthInput.value;
  const employee_id = codeInput.value.trim();

  let res;
  try {
    // Nếu không nhập mã nhân viên → lấy tất cả
    if (!employee_id) {
      console.log("run o day 1");
      res = await fetch(API_FIND_DATE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ work_date }),
      });
    } else {
      console.log("run o day 2");
      res = await fetch(API_FIND_CODE_DATE, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ work_date, employee_id }),
      });
    }

    const attendance = await res.json();
    console.log(attendance);
    window.attendanceList = attendance["data"] || [];

    tbody.innerHTML = ""; 

    if (!Array.isArray(window.attendanceList) || window.attendanceList.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4">Không có dữ liệu</td></tr>`;
      return;
    }


    paginateTable(window.attendanceList, 10, tbody, pagination, (att) => {
      const tr = document.createElement("tr");
      tr.classList.add("hover:bg-gray-50", "transition");

      tr.innerHTML = `
        <td class="py-3 px-4">${att.employee_id}</td>
        <td class="py-3 px-4 font-medium text-gray-800">${att.employee_name}</td>
        <td class="py-3 px-4 text-gray-600">${att.work_date}</td>
        <td class="py-3 px-4 text-gray-600">${att.check_in || "—"}</td>
        <td class="py-3 px-4 text-gray-600">${att.check_out || "—"}</td>
        <td class="py-3 px-4 text-gray-600">${att.note ? att.note : "—"}</td>
        <td class="py-3 px-4 text-center">
          <div class="inline-flex items-center space-x-2">
            <button class="edit-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm flex items-center gap-1" data-id="${att.id}">
              <i class="fa fa-edit"></i> Sửa
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

tbody.addEventListener("click", (e) => {
  const btn = e.target.closest(".edit-btn");
  if (!btn) return;

  const id = btn.dataset.id;
  const record = window.attendanceList.find((a) => a.id == id);
  if (!record) return alert("Không tìm thấy bản ghi!");

  document.getElementById("editId").value = record.id;
  document.getElementById("editCheckIn").value = record.check_in
    ? record.check_in.replace(" ", "T")
    : "";
  document.getElementById("editCheckOut").value = record.check_out
    ? record.check_out.replace(" ", "T")
    : "";
  document.getElementById("editNote").value = record.note || "";

  modal.classList.remove("hidden");
  modal.classList.add("flex");
});

cancelEdit.addEventListener("click", () => {
  modal.classList.add("hidden");
  modal.classList.remove("flex");
});

form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const id = document.getElementById("editId").value;
  const check_in = document.getElementById("editCheckIn").value.replace("T", " ") + ":00";
  const check_out = document.getElementById("editCheckOut").value.replace("T", " ") + ":00";
  const note = document.getElementById("editNote").value;

  try {
    const res = await fetch(API_EDIT, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, check_in, check_out, note }),
    });

    const result = await res.json();
    if (result.success) {
      alert("Cập nhật thành công!");
      modal.classList.add("hidden");
      loadAttendance();
    } else {
      alert(result.message || "Không thể cập nhật!");
    }
  } catch (err) {
    console.error(err);
    alert("Lỗi khi cập nhật!");
  }
});


// ✅ Format ngày giờ chuẩn
function formatDateTime(date = new Date()) {
  const Y = date.getFullYear();
  const M = String(date.getMonth() + 1).padStart(2, "0");
  const D = String(date.getDate()).padStart(2, "0");
  const h = String(date.getHours()).padStart(2, "0");
  const m = String(date.getMinutes()).padStart(2, "0");
  const s = String(date.getSeconds()).padStart(2, "0");
  return `${Y}-${M}-${D} ${h}:${m}:${s}`;
}

// ✅ Load bảng khi mở trang
loadAttendance();
