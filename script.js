const entryForm = document.getElementById("entry-form");
const doneDialog = document.getElementById("doneDialog");
const returnHomeBtn = document.getElementById("returnHome");

entryForm.addEventListener("submit", (e) => {
    e.preventDefault();
    doneDialog.showModal();
})

