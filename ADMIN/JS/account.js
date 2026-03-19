document.addEventListener("DOMContentLoaded", () => {

  // IMAGE UPLOAD + PREVIEW
  function setupUpload(boxId, inputId) {
  const box = document.getElementById(boxId);
  const input = document.getElementById(inputId);

  if (!box || !input) return;

  // ENSURE PHP IMAGE IS NOT LOST
  const current = box.dataset.current;
  if (current && !box.querySelector("img")) {
    const img = document.createElement("img");
    img.src = "/CAPSTONE/" + current;
    img.style.width = "100%";
    img.style.height = "100%";
    img.style.objectFit = "cover";
    img.style.borderRadius = "10px";
    box.innerHTML = "";
    box.appendChild(img);
  }

  box.addEventListener("click", () => input.click());

  input.addEventListener("change", () => {
    const file = input.files[0];
    if (!file) return;

    const imgURL = URL.createObjectURL(file);
    const existingImg = box.querySelector("img");

    if (existingImg) {
      existingImg.src = imgURL;
    } else {
      const img = document.createElement("img");
      img.src = imgURL;
      img.style.width = "100%";
      img.style.height = "100%";
      img.style.objectFit = "cover";
      img.style.borderRadius = "10px";
      box.innerHTML = "";
      box.appendChild(img);
    }
  });
}


  // Main images
  setupUpload("homeBox", "homeInput");
  setupUpload("shopBox", "shopInput");

  // Category images
  setupUpload("cat1", "catInput1");
  setupUpload("cat2", "catInput2");
  setupUpload("cat3", "catInput3");

  // SUCCESS TOAST HANDLING
  const toast = document.getElementById("successToast");

  if (toast) {
    // hide after 10 seconds
    setTimeout(() => {
      toast.classList.add("hide");
    }, 10000);

    // remove from DOM
    setTimeout(() => {
      toast.remove();
    }, 10400);

    // remove success=1 from URL
    const url = new URL(window.location);
    url.searchParams.delete("success");
    window.history.replaceState({}, document.title, url.pathname);
  }

});

function toggleWithdrawPIN() {
  const input = document.getElementById("pinWithdraw");

  if (input.type === "password") {
    input.type = "text";
  } else {
    input.type = "password";
  }
}

function showPIN() {
  document.getElementById("pinSection").style.display = "block";
}

function submitWithPIN() {
  const pin = document.getElementById("confirmPIN").value;

  if (pin === "") {
    alert("Enter PIN");
    return;
  }

  document.getElementById("accountForm").submit();
}

function togglePIN() {
  const input = document.getElementById("pinAction");
  const openEye = document.getElementById("eyeOpenAdmin");
  const closedEye = document.getElementById("eyeClosedAdmin");

  if (input.type === "password") {
    input.type = "text"; // 👁 show
  } else {
    input.type = "password"; // hide
  }
}

//
function toggleAdminPassword() {
  const input = document.getElementById("adminPassword");
  const openEye = document.getElementById("eyeOpenAdmin");
  const closedEye = document.getElementById("eyeClosedAdmin");

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

function toggleInput(inputId, openEyeId, closedEyeId) {
  const input = document.getElementById(inputId);
  const openEye = document.getElementById(openEyeId);
  const closedEye = document.getElementById(closedEyeId);

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

window.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("adminPassword");

  if (input && input.value !== "") {
    input.type = "text"; // 👁 force show after refresh
  }
});
