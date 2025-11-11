const navLinks = document.querySelectorAll("aside nav a");
const mainContent = document.getElementById("main_content");
const name = document.getElementById("name");

document.addEventListener("DOMContentLoaded", () => {
  navLinks.forEach(link => {
    link.addEventListener("click", async (e) => {
      e.preventDefault();

      // Xóa highlight
      navLinks.forEach(l => {
        l.classList.remove("bg-blue-100", "text-blue-600");
      });

      // Thêm highlight
      link.classList.add("bg-blue-100", "text-blue-600");

      // Xác định trang cần load
      const page = link.textContent.trim();

      if (page === "Trang chủ") {
        await loadPage('./homepage');
      } else if (page === "Quản lý tài khoản") {
        await loadPage('./accountpage');
      } else if (page === "Nhân sự") {
        await loadPage('./employeepage');
      }else if (page ==='Phòng ban'){
        await loadPage("./departmentpage")
      }else if (page === 'Lương'){
        await loadPage("./salarypage")
      }else if (page === 'Vị trí'){
        await loadPage("./positionpage")
      } else if (page === 'Chấm công'){
        await loadPage("./attendancepage")
      } else if (page === 'Đánh giá'){
        await loadPage("./reviewpage")
      }
       else {
        mainContent.innerHTML = `
          <h1 class="text-2xl font-bold mb-4">${page}</h1>
          <div class="bg-white p-4 rounded shadow">Trang này đang được phát triển...</div>
        `;
      }
    });
  });
});

async function loadPage(url) {
  try {
    // Thêm timestamp để tránh cache
    const res = await fetch(`${url}?v=${Date.now()}`, { cache: "no-store" });
    const html = await res.text();

    // Gán nội dung 
    mainContent.innerHTML = html;

    //  script
    executeScripts(mainContent, url);

  } catch (err) {
    console.error("Lỗi khi load trang:", err);
    mainContent.innerHTML = `<p class="text-red-500">Không thể tải trang: ${url}</p>`;
  }
}

function executeScripts(container, baseUrl) {
  const scripts = container.querySelectorAll("script");

  scripts.forEach(oldScript => {
    const newScript = document.createElement("script");
    newScript.type = oldScript.type || "text/javascript";

    if (oldScript.src) {
      let src = oldScript.getAttribute("src");
      if (!src.startsWith("http") && !src.startsWith("/")) {
        const base = baseUrl.substring(0, baseUrl.lastIndexOf("/") + 1);
        src = base + src;
      }
      //  thêm timestamp tránh cache script
      newScript.src = `${src}?v=${Date.now()}`;
    } else {
      newScript.textContent = oldScript.textContent;
    }

    // Gắn script vào DOM để thực thi
    container.appendChild(newScript);
  });
}