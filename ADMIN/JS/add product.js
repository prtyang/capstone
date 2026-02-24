let variationState = {
  colors: [],
  sizes: [],
  data: {}
};

function previewSizeChart(input) {
  const file = input.files[0];
  if (!file) return;

  const box = input.closest(".size-chart-upload");

  const reader = new FileReader();
  reader.onload = function (e) {
    box.style.backgroundImage = `url(${e.target.result})`;
    box.style.backgroundSize = "contain";
    box.style.backgroundPosition = "center";
    box.style.backgroundRepeat = "no-repeat";

    const span = box.querySelector("span");
    if (span) span.style.display = "none";
  };

  reader.readAsDataURL(file);
}

/* IMAGE PREVIEW */
function previewImage(input) {
  const file = input.files[0];
  if (!file) return;

  const box = input.closest(".upload-box");
  if (!box) return;

  const reader = new FileReader();

  reader.onload = function (e) {
    box.style.backgroundImage = `url(${e.target.result})`;
    box.style.backgroundSize = "cover";
    box.style.backgroundPosition = "center";
    box.style.backgroundRepeat = "no-repeat";

    const span = box.querySelector("span");
    if (span) span.style.display = "none";
  };

  reader.readAsDataURL(file);
}

/* VALIDATION */
function validateProduct() {

  const requiredInputs = document.querySelectorAll(".required");
  for (let input of requiredInputs) {
    if (input.value.trim() === "") {
      alert("Please complete all required fields.");
      input.focus();
      return false;
    }
  }

const isEdit = document.getElementById("isEdit")?.value === "1";
const hasExistingImages = document.getElementById("hasExistingImages")?.value === "1";

const productImages = document.querySelectorAll(
  "input[name='product_images[]']"
);

let hasNewImage = false;
productImages.forEach(img => {
  if (img.files.length > 0) hasNewImage = true;
});

//  REQUIRE IMAGE ONLY IF:
// - adding new product
// - OR editing but product has NO existing images
if (!hasNewImage && (!isEdit || !hasExistingImages)) {
  alert("Please upload at least one product image.");
  return false;
}

  if (!confirm("Are you sure you want to upload this product?")) {
    return false;
  }

  // REMOVE PESO SIGN BEFORE SUBMIT
document.querySelectorAll(".price-input").forEach(input => {
  input.value = input.value.replace(/[^\d.]/g, "");
});


  return true;
}

function renderVariationsFromState() {

  const container = document.getElementById("generatedVariations");
  container.innerHTML = "";
  container.style.display = "block";

  variationState.colors.forEach(color => {

    const box = document.createElement("div");
    box.className = "variation-box";

    let html = `
      <div class="variation-header">
        <span>COLOR</span>
        <span></span>
        <span>SIZE</span>
        <span>PRICE</span>
        <span>QTY</span>
        <span>SKU</span>
      </div>

      <div class="variation-body">
        <div class="color-col">
          <div class="color-name">${color}</div>

    <label class="upload-box"
        ${variationState.data[color][variationState.sizes[0]]?.image
    ? `style="background-image:url('../../uploads/${variationState.data[color][variationState.sizes[0]].image}');
      background-size:cover;background-position:center;"`
    : ''}>

      <input type="file"
        name="variation_image[${color}]"
        onchange="handleVariationImage(this, '${color}')">

      <span ${variationState.data[color][variationState.sizes[0]]?.image ? 'style="display:none"' : ''}>
        UPLOAD<br>PHOTO
      </span>
    </label>

      </div>

        <div class="divider"></div>
        <div class="rows">
    `;

    variationState.sizes.forEach(size => {
      const row = variationState.data[color][size];

      html += `
        <div class="row">
          <input type="hidden" name="variation_color[]" value="${color}">
          <input type="hidden" name="variation_size[]" value="${size}">
          <input type="hidden"
            name="variation_id[]"
            value="${variationState.data[color][size]?.id || ''}">


          <input class="size-box" value="${size}" readonly>

          <input class="price-box price-input"
              type="text"
              name="variation_price[]"
              value="${row.price ? '₱ ' + row.price : ''}">

          <input class="qty-box"
              type="number"
              name="variation_qty[]"
              value="${row.qty}">

          <input class="sku-box"
              type="text"
              name="variation_sku[]"
              value="${row.sku}">
        </div>
      `;
    });

    html += `
        </div>
      </div>
    `;

    box.innerHTML = html;
    container.appendChild(box);
  });
}


/* VARIATION GENERATOR */
document.addEventListener("DOMContentLoaded", () => {

  const colorInput = document.getElementById("variationColors");
  const sizeInput  = document.getElementById("variationSizes");
  const container  = document.getElementById("generatedVariations");
  const addBtn     = document.getElementById("addVariationBtn");

addBtn.addEventListener("click", () => {

const newColors = colorInput.value
  .split(",")
  .map(c => c.trim().toUpperCase())
  .filter(Boolean);

const newSizes = sizeInput.value
  .split(",")
  .map(s => s.trim().toUpperCase())
  .filter(Boolean);

// MERGE colors
newColors.forEach(color => {
  if (!variationState.colors.includes(color)) {
    variationState.colors.push(color);
    variationState.data[color] = {};
  }
});

// MERGE sizes
newSizes.forEach(size => {
  if (!variationState.sizes.includes(size)) {
    variationState.sizes.push(size);
  }
});

// Ensure every color has every size
variationState.colors.forEach(color => {
  variationState.sizes.forEach(size => {
    if (!variationState.data[color][size]) {
      variationState.data[color][size] = {
        price: "",
        qty: "",
        sku: "",
        image: variationState.data[color]?.[variationState.sizes[0]]?.image || null
      };
    }
  });
});

renderVariationsFromState();

// MERGE colors
newColors.forEach(color => {
  if (!variationState.colors.includes(color)) {
    variationState.colors.push(color);
    variationState.data[color] = {};
  }
});

// MERGE sizes
newSizes.forEach(size => {
  if (!variationState.sizes.includes(size)) {
    variationState.sizes.push(size);
  }
});

// REMOVE deleted sizes
variationState.colors.forEach(color => {
  Object.keys(variationState.data[color]).forEach(size => {
    if (!variationState.sizes.includes(size)) {
      delete variationState.data[color][size];
    }
  });
});

// REMOVE deleted colors
Object.keys(variationState.data).forEach(color => {
  if (!variationState.colors.includes(color)) {
    delete variationState.data[color];
  }
});

  // FILL COLOR & SIZE TEXTBOXES IN EDIT MODE
document.getElementById("variationColors").value =
  variationState.colors.join(", ");

document.getElementById("variationSizes").value =
  variationState.sizes.join(", ");

  renderVariationsFromState();
});

});

//edit mode
document.addEventListener("DOMContentLoaded", () => {

  if (!window.existingVariations) return;

  variationState.colors = [];
  variationState.sizes = [];
  variationState.data = {};

  window.existingVariations.forEach(v => {

    if (!variationState.colors.includes(v.color)) {
      variationState.colors.push(v.color);
    }

    if (!variationState.sizes.includes(v.size)) {
      variationState.sizes.push(v.size);
    }

    if (!variationState.data[v.color]) {
      variationState.data[v.color] = {};
    }

    variationState.data[v.color][v.size] = {
      price: v.price,
      qty: v.qty,
      sku: v.sku,
      image: v.image || null,
      id: v.id
    };
  });

  // AUTO-FILL COLOR & SIZE TEXTBOXES (EDIT MODE)
document.getElementById("variationColors").value =
  variationState.colors.join(", ");

document.getElementById("variationSizes").value =
  variationState.sizes.join(", ");


  renderVariationsFromState();
});


// PESO SIGN WHILE TYPING (LIVE FORMAT)
document.addEventListener("input", function (e) {

  if (!e.target.classList.contains("price-input")) return;

  let value = e.target.value;

  // remove everything except numbers and dot
  value = value.replace(/[^\d.]/g, "");

  if (value === "") {
    e.target.value = "";
    return;
  }

  e.target.value = "₱ " + value;
});


document.addEventListener("input", e => {

  const row = e.target.closest(".row");
  if (!row) return;

  const color = row.querySelector("input[name='variation_color[]']").value;
  const size  = row.querySelector("input[name='variation_size[]']").value;

  if (e.target.classList.contains("price-input")) {
    variationState.data[color][size].price =
      e.target.value.replace(/[^\d.]/g, "");
  }

  if (e.target.classList.contains("qty-box")) {
    variationState.data[color][size].qty = e.target.value;
  }

  if (e.target.classList.contains("sku-box")) {
    variationState.data[color][size].sku = e.target.value;
  }
});

function handleVariationImage(input, color) {
  const file = input.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = e => {
    variationState.colors.forEach(c => {
      if (!variationState.data[c]) return;
      Object.keys(variationState.data[c]).forEach(size => {
        variationState.data[c][size].image = file.name;
      });
    });

    input.parentElement.style.backgroundImage = `url(${e.target.result})`;
    input.parentElement.querySelector("span")?.style.setProperty("display","none");
  };

  reader.readAsDataURL(file);
}

/* AUTO-EXPAND TEXTAREA (DESCRIPTION) */
function autoExpandTextarea(el) {
  el.style.height = "auto";
  el.style.height = el.scrollHeight + "px";
}

// Expand while typing
document.addEventListener("input", function (e) {
  if (e.target.classList.contains("auto-expand")) {
    autoExpandTextarea(e.target);
  }
});

// Expand on page load (EDIT MODE)
window.addEventListener("load", function () {
  document.querySelectorAll(".auto-expand").forEach(autoExpandTextarea);
});
