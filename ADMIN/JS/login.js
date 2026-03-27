function togglePassword() {
  const input = document.getElementById("password");
  const openEye = document.getElementById("eyeOpen");
  const closedEye = document.getElementById("eyeClosed");

  if (input.type === "password") {
    input.type = "text";
    openEye.style.display = "none";
    closedEye.style.display = "block";
  } else {
    input.type = "password";
    openEye.style.display = "block";
    closedEye.style.display = "none";
  }
}

//
const btn = document.getElementById("loginBtn");

btn.onclick = async () => {

  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  const res = await fetch("login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ email, password })
  });

  const data = await res.json();

  if(data.status === "success"){
    window.location.href = "dashboard.php";
  } 
  else if(data.status === "wrong_password"){
    alert("Wrong password");
  } 
  else {
    alert("Email not found");
  }
};

function togglePassword() {
  const input = document.getElementById("password");
  const openEye = document.getElementById("eyeOpen");
  const closedEye = document.getElementById("eyeClosed");

  if (input.type === "password") {
    input.type = "text";
    openEye.style.display = "none";
    closedEye.style.display = "block";
  } else {
    input.type = "password";
    openEye.style.display = "block";
    closedEye.style.display = "none";
  }
}

//
function showForgot(){
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("forgotBox").style.display = "block";
}

function backToLogin(){
  document.getElementById("loginForm").style.display = "block";
  document.getElementById("forgotBox").style.display = "none";
}

// SEND OTP
function sendOTP(){
  const email = document.getElementById("resetEmail").value;

  fetch("send_otp.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: "email=" + email
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    document.getElementById("otpBox").style.display = "block";
  });
}

// VERIFY OTP
function verifyOTP(){
  const email = document.getElementById("resetEmail").value;
  const otp = document.getElementById("otp").value;

  fetch("verify_otp.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}`
  })
  .then(res => res.text())
  .then(data => {
    if(data.trim() === "success"){
      alert("OTP Verified!");
      document.getElementById("newPassBox").style.display = "block";
    } else {
      alert("Invalid OTP");
    }
  });
}

// RESET PASSWORD
function resetPassword(){
  const email = document.getElementById("resetEmail").value;
  const otp = document.getElementById("otp").value;
  const password = document.getElementById("newPassword").value;

  fetch("reset_password.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}&password=${password}`
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    backToLogin();
  });
}
// SEND OTP
function sendOTP(event){
  const email = document.getElementById("resetEmail").value;

  // 🔥 get the button
  const btn = event.target;

  // disable button
  btn.disabled = true;
  btn.innerText = "Sending...";

  fetch("send_otp.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: "email=" + email
  })
  .then(res => res.text())
  .then(data => {
    alert(data);

    // show OTP input
    document.getElementById("otpBox").style.display = "block";

    // re-enable button after 5 sec
    setTimeout(() => {
      btn.disabled = false;
      btn.innerText = "Send OTP";
    }, 5000);
  });
}

// VERIFY OTP
function verifyOTP(){
  const email = document.getElementById("resetEmail").value.trim();
  const otp = document.getElementById("otp").value.trim();

  fetch("verify_otp.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}`
  })
  .then(res => res.text())
  .then(data => {
    console.log("VERIFY:", data);

    if(data === "success"){
      alert("OTP Verified!");
      document.getElementById("newPassBox").style.display = "block";
    } 
    else if(data === "expired"){
      alert("OTP expired!");
    }
    else if(data === "wrong"){
      alert("Wrong OTP!");
    }
    else {
      alert("Error: " + data);
    }
  });
}

// RESET PASSWORD
function resetPassword(){
  const email = document.getElementById("resetEmail").value;
  const otp = document.getElementById("otp").value;
  const password = document.getElementById("newPassword").value;

  fetch("reset_password.php", {
    method: "POST",
    headers: {"Content-Type":"application/x-www-form-urlencoded"},
    body: `email=${email}&otp=${otp}&password=${password}`
  })
  .then(res => res.text())
  .then(data => {
    alert(data);
    backToLogin();
  });
}

//
function toggleNewPassword() {
  const input = document.getElementById("newPassword");
  const openEye = document.getElementById("newEyeOpen");
  const closedEye = document.getElementById("newEyeClosed");

  if (input.type === "password") {
    input.type = "text";
    openEye.style.display = "none";
    closedEye.style.display = "block";
  } else {
    input.type = "password";
    openEye.style.display = "block";
    closedEye.style.display = "none";
  }
}