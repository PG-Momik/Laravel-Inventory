var imageUploadFacade = document.getElementById('imageUploadFacade');
imageUploadFacade.addEventListener('click', function () {
    var imageUploadField = document.getElementById('imageUploadField');
    imageUploadField.click();
})
var imageUploadField = document.getElementById('imageUploadField');
imageUploadField.addEventListener('change', function () {
    var actualImage = document.getElementById('actualImage')
    actualImage.src = URL.createObjectURL(event.target.files[0]);
    actualImage.onload = function () {
        URL.revokeObjectURL(actualImage.src) // free memory
    }
})

function add(i) {
    calcField = document.getElementsByClassName('calcField')[i];
    value = calcField.value;
    value++;
    calcField.value = value;

}

function subtract(i) {
    calcField = document.getElementsByClassName('calcField')[i];
    value = calcField.value;
    if (value >= 0) {
        value--;
        calcField.value = value;
    } else {
        calcField.classList.add('is-invalid');
    }
}


//For modal and all other new behaviour

const initialQuantity = document.getElementById('productQuantity').value;
console.log(initialQuantity);
const updateBtn = document.getElementById('updateBtn');

updateBtn.addEventListener('click', function () {

    const quantityAtClick = document.getElementById('productQuantity').value;
    const changes = quantityAtClick - initialQuantity;

    document.getElementById('transactionUnits').textContent = Math.abs(changes);
    const priceField = document.getElementById('priceField');

    if (changes > 0) {
        resetModal();
        document.getElementById('transactionType').textContent = "Purchase";
        priceField.setAttribute('placeholder', `Enter Purchase price.`)
        createHiddenFieldInModal("Purchase");
    } else if (changes < 0) {
        resetModal();
        document.getElementById('transactionType').textContent = "Sales";
        priceField.setAttribute('placeholder', `Enter Sales price.`)
        createDiscountField();
        createHiddenFieldInModal("Sale");

    } else {
        resetModal();
        document.getElementById('noChanges').textContent = " No ";
        document.getElementById('priceField').required = false;
        hideModalBody();

    }

    const myModal = new bootstrap.Modal(document.getElementById("changesModal"), {});
    myModal.show()

})


function hideModalBody() {
    document.getElementsByClassName('modal-body')[0].classList.add('hidden');
}

function hideModalFooter() {
    document.getElementsByClassName('modal-footer')[0].classList.add('hidden');
}

function resetModal() {

    document.getElementById('noChanges').textContent = "";
    document.getElementsByClassName('modal-body')[0].classList.remove('hidden');
    document.getElementsByClassName('modal-footer')[0].classList.remove('hidden');
    if (document.contains(document.getElementById("discountField"))) {
        document.getElementById("discountField").remove();
    }
}

function createDiscountField() {
    const discountField = document.createElement('input');
    discountField.setAttribute('type', 'number');
    discountField.setAttribute('name', 'discount');
    discountField.setAttribute('min', '0');
    discountField.setAttribute('placeholder', 'Enter discount. (Default 0)');
    discountField.setAttribute('autocomplete', 'off');
    discountField.classList.add('form-control', 'my-3');
    discountField.id = "discountField"
    document.getElementsByClassName('modal-body')[0].appendChild(discountField);
}

function createHiddenFieldInModal(type) {
    if (document.contains(document.getElementById("hiddenTransactionTypeField"))) {
        document.getElementById("hiddenTransactionTypeField").remove();
    }

    const hiddenField = document.createElement('input');
    hiddenField.classList.add('hidden');
    hiddenField.setAttribute('name', 'transactionType');
    hiddenField.setAttribute('value', type);
    hiddenField.id = 'hiddenTransactionTypeField'

    document.getElementsByClassName('modal-body')[0].appendChild(hiddenField);

}
