function openReturnDetails(order) {

  document.getElementById("returnDetailsModal").style.display = "flex";

  // MESSAGE
  document.getElementById("returnMessage").innerText =
    "Reason: " + (order.refund_message || "No message");

  // MEDIA
  const container = document.getElementById("returnImages");
  container.innerHTML = "";

  if (order.refund_images) {
    try {
      const files = JSON.parse(order.refund_images);

      files.forEach(file => {
        let el;

        if (file.match(/\.(mp4|webm|ogg)$/i)) {
          el = document.createElement("video");
          el.controls = true;
        } else {
          el = document.createElement("img");
        }

        el.src = "../../uploads/" + file;
        container.appendChild(el);
      });

    } catch (e) {
      console.error("Invalid JSON", e);
    }
  }
}

function closeReturnDetails() {
  document.getElementById("returnDetailsModal").style.display = "none";
}