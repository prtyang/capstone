<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wishlist</title>
</head>
<body>

<h2>My Wishlist ❤️</h2>
<div id="wishlist"></div>

<script>
  const wishlist = JSON.parse(localStorage.getItem("wishlist")) || [];
  const container = document.getElementById("wishlist");

  if (wishlist.length === 0) {
    container.innerHTML = "<p>No items yet.</p>";
  } else {
    wishlist.forEach(item => {
      container.innerHTML += `
        <div>
          <h4>${item.name}</h4>
          <p>₱${item.price}</p>
        </div>
      `;
    });
  }
</script>

</body>
</html>
