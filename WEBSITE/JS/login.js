const goRegister = document.getElementById("goRegister");
const goLogin = document.getElementById("goLogin");

const loginForm = document.querySelector(".login");
const registerForm = document.querySelector(".register");

let otpVerified = false;
// 👉 SWITCH TO REGISTER
goRegister.addEventListener("click", () => {
  loginForm.style.display = "none";
  registerForm.style.display = "block";
});

// 👉 SWITCH TO LOGIN
goLogin.addEventListener("click", () => {
  loginForm.style.display = "block";
  registerForm.style.display = "none";
});

//OPEN MODAL OTP
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

// SWITCH PANEL TO FORGOT PASSWORD
document.addEventListener("DOMContentLoaded", () => {
  const forgotBtn = document.getElementById("forgotBtn");

  if (forgotBtn) {
    forgotBtn.addEventListener("click", () => {
      document.getElementById("defaultPanel").style.display = "none";
      document.getElementById("forgotPanel").style.display = "block";
    });
  }
});

// BACK TO LOGIN PANEL
function backToLogin(){
  document.getElementById("defaultPanel").style.display = "block";
  document.getElementById("forgotPanel").style.display = "none";
}

// SEND OTP
function sendOTP(){
  const email = document.getElementById("resetEmail").value;

  fetch("../HTML/send_otp.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: "email=" + email
  })
  .then(res => res.text())
  .then(data => {
  console.log("SERVER:", data);
    alert(data);
    document.getElementById("otpBox").style.display = "block";
  });
}

// VERIFY OTP
function verifyOTP(){
  const email = document.getElementById("resetEmail").value;
  const otp = document.getElementById("otp").value;

  fetch("../HTML/verify_otp.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}`
  })
  .then(res => res.text())
  .then(data => {
  if(data.trim() === "success"){
    alert("OTP Verified!");
    otpVerified = true; // IMPORTANT
    document.getElementById("newPassBox").style.display = "block";
  } else {
      alert("Invalid OTP");
    }
  });
}

// RESET PASSWORD
function resetPassword(){
  if(!otpVerified){
    alert("Please verify OTP first");
    return;
  }

  const email = document.getElementById("resetEmail").value;
  const otp = document.getElementById("otp").value;
  const password = document.getElementById("newPassword").value;

  fetch("../HTML/reset_password.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}&password=${password}`
  })
  .then(res => res.text())
  .then(data => {
  alert(data);

  if(data.includes("Password updated")){
    // reset UI
    document.getElementById("newPassBox").style.display = "none";
    document.getElementById("otpBox").style.display = "none";
    document.getElementById("forgotPanel").style.display = "none";
    document.getElementById("defaultPanel").style.display = "block";

    // clear inputs
    document.getElementById("resetEmail").value = "";
    document.getElementById("otp").value = "";
    document.getElementById("newPassword").value = "";

    // OPTIONAL: auto switch to login form
    loginForm.style.display = "block";
    registerForm.style.display = "none";
  }
});
}