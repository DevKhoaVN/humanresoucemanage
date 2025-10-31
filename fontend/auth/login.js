
const loginForm = document.getElementById('loginForm');
const errorMsg = document.getElementById('error');

loginForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;


    if(username === 'admin' && password === '123') {
        console.log("loging...")
        window.location.href = './../dashboard.html'; 
    } else {
        errorMsg.classList.remove('hidden');
    }
}
)
