const loginForm = document.getElementById('loginForm');
const errorMsg = document.getElementById('error');

loginForm.addEventListener('submit', async function (e) {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();


  if (!username || !password) {
    errorMsg.textContent = "Please fill in all fields!";
    errorMsg.classList.remove('hidden');
    return;
  }

  if (password.length < 3) {
    errorMsg.textContent = "Password must be at least 3 characters!";
    errorMsg.classList.remove('hidden');
    return;
  }

  try {
    // fetch + await + parse JSON
    const res = await fetch("http://localhost:63342/index.php?url=authencation/login", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ username, password }),
      credentials: "include"
    });

    const data = await res.json(); // ✅ parse JSON

   if (data.code === 200 && data.account) {
  localStorage.setItem("role", data.account.role);
  localStorage.setItem("name" , data.account.username)

  if (["admin", "boss"].includes(data.account.role)) {
    window.location.href = "../dashboard.html";
  }
} else {
  errorMsg.textContent = data.message || "Invalid username or password!";
  errorMsg.classList.remove("hidden");
}


  } catch (err) {
    console.error('Login failed:', err);
    errorMsg.textContent = "Server error, please try again!";
    errorMsg.classList.remove('hidden');
  }
});
