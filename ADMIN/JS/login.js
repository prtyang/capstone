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