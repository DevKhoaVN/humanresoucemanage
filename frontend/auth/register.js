const register = async () => {
    const registerForm = document.getElementById('registerForm');
    const errorMsg = document.getElementById('error');

    registerForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        errorMsg.classList.add('hidden'); 

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if(password !== confirmPassword) {
            errorMsg.textContent = "Mật khẩu và xác nhận mật khẩu không khớp!";
            errorMsg.classList.remove('hidden');
            return;
        }

        if(email === "" || password === "") {
            errorMsg.textContent = "Vui lòng điền đầy đủ thông tin!";
            errorMsg.classList.remove('hidden');
            return;
        }

        const payload = {username: email, password};
        const API_REGISTER = "https://quanlinhansu.infinityfreeapp.com/api?url=authencation/register";
        
        try {
            const res = await fetch(API_REGISTER, {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(payload)
            });

            const result = await res.json();

            if(result.code == 201) {
                alert("Đăng ký thành công! Hãy đăng nhập.");
                window.location.href = 'login.html';
            } else {
                errorMsg.textContent = result.message || "Lỗi server";
                errorMsg.classList.remove('hidden');
            }
        } catch(error) {
            errorMsg.textContent = "Lỗi kết nối!";
            errorMsg.classList.remove('hidden');
        }
    });
};

register(); 