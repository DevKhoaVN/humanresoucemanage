import paginateTable from "../utils/render.js";

const API_URL_SALARY = "https://quanlinhansu.infinityfreeapp.com/api?url=salary/gettAllSalaryInMonth";
const tbody = document.getElementById("employeeTableBody");
const monthInput = document.getElementById("monthSelect");
const caculateSalary = document.getElementById("caculateSalary");


const today = new Date();
monthInput.value = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}`;

monthInput.addEventListener("change", () => {
  loadSalaries();
});

  caculateSalary.addEventListener('click' , async (e) => {
      e.preventDefault();

      const API_CACULATE_SALARY = "https://quanlinhansu.infinityfreeapp.com/api?url=salary/saveSalary";

      const res =  await fetch(API_CACULATE_SALARY, {credentials: "include"})
      const result = await   res.json()

      if(result.code == 200){
          alert("Tính lươn thành công")
      }
  })


async function loadSalaries() {
  const [year, month] = monthInput.value.split("-");
   const res = await fetch(`${API_URL_SALARY}`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ year , month }),
      credentials: "include"
  });
  const salaries = await res.json();

  const salariesList = salaries['data'] || null;
  tbody.innerHTML = ""; 

  salariesList.forEach( salary => {
    const tr = document.createElement("tr");
    tr.innerHTML = `
      <td class="px-3 py-2 text-gray-700">${salary.id}</td>
      <td class="px-3 py-2 font-medium text-gray-800">${salary.employee_name}</td>
      <td class="px-3 py-2 text-gray-600">${salary.position}</td>
      <td class="px-3 py-2 text-left text-gray-600">${salary.absent_days}</td>
      <td class="px-3 py-2 text-center text-gray-600">${salary.work_days}</td>
      <td class="px-3 py-2 text-center text-gray-600">
        ${salary.base_salary.toLocaleString('vi-VN', { maximumFractionDigits: 0 })}₫
      </td>
      <td class="px-3 py-2 text-center font-semibold text-green-600">
        ${salary.total_salary.toLocaleString('vi-VN', { maximumFractionDigits: 0 })}₫
      </td>
    `;
    tbody.appendChild(tr);
  });

  paginateTable("employee_table");
}


loadSalaries();
