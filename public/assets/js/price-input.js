
    const amountInput = document.getElementById("price");
amountInput.addEventListener("input", function(e) {
  let value = this.value.replace(/[^0-9]/g, "");
  if (value.length > 3) {
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  this.value = value;
});
amountInput.addEventListener("paste", function(e) {
  const pastedData = e.clipboardData.getData("text");
  if (!/^\d+$/.test(pastedData)) {
    e.preventDefault();
  }
});
const form = document.querySelector(".form");

form.addEventListener("submit", function (e) {
  amountInput.value = amountInput.value.replace(/,/g, "");
});




