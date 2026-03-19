document.addEventListener("DOMContentLoaded", function(){

const discountInput = document.getElementById("discountValue");
const buttons = document.querySelectorAll(".discount-type button");
const discountTypeInput = document.getElementById("discountType");

const addProductBtn = document.getElementById("addProductBtn")
const modal = document.getElementById("productModal")

let discountType = "percentage";


// ADD PRODUCT BUTTON
addProductBtn.addEventListener("click", function(){

modal.style.display = "flex"

fetch("get-products.php")
.then(res => res.text())
.then(data => {

document.getElementById("productList").innerHTML = data

})

})


// CLOSE MODAL
window.closeProductModal = function(){
modal.style.display = "none"
}

//Search
document.addEventListener("input", function(e){

if(e.target.id === "productSearch"){

let search = e.target.value.toLowerCase()

let products = document.querySelectorAll(".product-select")

products.forEach(product => {

let name = product.innerText.toLowerCase()

if(name.includes(search)){
product.style.display = "flex"
}else{
product.style.display = "none"
}

})

}

})


// DISCOUNT BUTTONS
buttons.forEach(btn => {

btn.addEventListener("click", function(){

buttons.forEach(b => b.classList.remove("active"));
this.classList.add("active");

discountType = this.dataset.type;

discountTypeInput.value = discountType;

updatePrices();

});

});


// DISCOUNT INPUT
discountInput.addEventListener("input", updatePrices);


// UPDATE PRICES
function updatePrices(){

let discount = parseFloat(discountInput.value) || 0;

let rows = document.querySelectorAll("#selectedProducts tr");

rows.forEach(row=>{

let price = parseFloat(row.dataset.price);

let final;

if(discountType === "percentage"){
final = price - (price * discount / 100);
row.querySelector(".discount").textContent = discount + "%";
}else{
final = price - discount;
row.querySelector(".discount").textContent = "₱" + discount;
}

row.querySelector(".final").textContent = "₱" + final.toFixed(2);

});

}


// SELECT PRODUCT
document.addEventListener("change",function(e){

if(e.target.classList.contains("productCheck")){

let id = e.target.dataset.id
let name = e.target.dataset.name
let price = e.target.dataset.price
let image = e.target.dataset.image

let table = document.getElementById("selectedProducts")

let row = `
<tr data-id="${id}" data-price="${price}">

<td class="product-cell">
<div class="product-info">
<img src="../../uploads/${image}" class="product-thumb">

<div class="product-text">
${name}
</div>

</div>
</td>

<td>₱${price}</td>

<td class="discount">0</td>

<td class="final">₱${price}</td>

<td>
<button type="button" class="remove-btn" onclick="removeProduct(this)">
Remove
</button>
</td>

<input type="hidden" name="products[]" value="${id}">

</tr>
`

table.insertAdjacentHTML("beforeend",row)

updatePrices()

}

})

// REMOVE PRODUCT
window.removeProduct = function(btn){
btn.closest("tr").remove()
}

});

//x
window.onclick = function(event){

const modal = document.getElementById("productModal");

if(event.target === modal){
modal.style.display = "none";
}

}

document.querySelector("form").addEventListener("submit", function(e){

let start = document.querySelector("[name='start_date']").value;
let end = document.querySelector("[name='end_date']").value;
let products = document.querySelectorAll("input[name='products[]']");

if(!start || !end){
    alert("Please select start and end date");
    e.preventDefault();
    return;
}

if(products.length === 0){
    alert("Please add at least 1 product");
    e.preventDefault();
    return;
}

if(new Date(end) < new Date(start)){
    alert("End date cannot be earlier than start date");
    e.preventDefault();
    return;
}

});