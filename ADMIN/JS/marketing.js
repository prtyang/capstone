let deleteID = null;

function openDeleteModal(id){
    deleteID = id;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeDeleteModal(){
    document.getElementById("deleteModal").style.display = "none";
}

function confirmDelete(){

let pin = document.getElementById("deletePIN").value;

fetch("../../config/delete_voucher.php",{

method:"POST",
headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`voucher_id=${deleteID}&pin=${pin}`

})
.then(res=>res.text())
.then(data=>{

data = data.trim();

if(data === "success"){
alert("Voucher deleted");
location.reload();
}
else{
alert("Wrong PIN");
}

});

}
function viewPromotionProducts(promoID, row){

document.getElementById("promotionModal").style.display = "flex";

let isEndingToday = row.dataset.ending === "1";

let note = "";

if(isEndingToday){
    note = `
    <div class="warning-note">
        ⚠️ This promotion will be deleted in 3 days and you will not have a copy.
    </div>
    `;
}

fetch("../../config/get_promotion_products.php?promo_id=" + promoID)
.then(res => res.text())
.then(data => {

document.getElementById("promotionProducts").innerHTML = note + data;

});

}

function closePromotionModal(){
document.getElementById("promotionModal").style.display = "none";
}