document.addEventListener("DOMContentLoaded", () => {

  // IMAGE UPLOAD + PREVIEW
  function setupUpload(boxId, inputId) {
  const box = document.getElementById(boxId);
  const input = document.getElementById(inputId);

  if (!box || !input) return;

  // 🔥 ENSURE PHP IMAGE IS NOT LOST
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
