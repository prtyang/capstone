const formTitle = document.getElementById("formTitle");
const formBtn = document.getElementById("formBtn");
const emailField = document.getElementById("emailField");
const toggleText = document.getElementById("toggleText");

let isLogin = true;

function setupToggle() {
  document.getElementById("switchMode").addEventListener("click", () => {
    isLogin = !isLogin;

    if(isLogin){
      formTitle.textContent = "LOGIN";
      formBtn.textContent = "Login";
      emailField.style.display = "none";
      toggleText.innerHTML = `Don't have an account? <span id="switchMode">Register</span>`;
    } else {
      formTitle.textContent = "REGISTER";
      formBtn.textContent = "Register";
      emailField.style.display = "block";
      toggleText.innerHTML = `Already have an account? <span id="switchMode">Login</span>`;
    }

    setupToggle(); // re-bind click
  });
}

const goRegister = document.getElementById("goRegister");
const goLogin = document.getElementById("goLogin");

const loginForm = document.querySelector(".login");
const registerForm = document.querySelector(".register");

const leftPanel = document.querySelector(".panel .left");
const rightPanel = document.querySelector(".panel .right");

// 👉 SWITCH TO REGISTER
goRegister.addEventListener("click", () => {
  loginForm.style.display = "none";
  registerForm.style.display = "block";

  // panel text
  leftPanel.style.opacity = "1";
  rightPanel.style.opacity = "0";
});

// 👉 SWITCH TO LOGIN
goLogin.addEventListener("click", () => {
  loginForm.style.display = "block";
  registerForm.style.display = "none";

  // panel text
  leftPanel.style.opacity = "0";
  rightPanel.style.opacity = "1";
});

document.querySelectorAll(".togglePassword").forEach(toggle => {
  toggle.addEventListener("click", () => {
    const input = document.getElementById(toggle.dataset.target);

    if (input.type === "password") {
      input.type = "text";
      toggle.classList.add("active");
    } else {
      input.type = "password";
      toggle.classList.remove("active");
    }
  });
});