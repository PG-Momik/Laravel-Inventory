const formSelects = document.getElementsByClassName('form-select');
const confirmCheck = document.getElementById('confirmCheck');
var moneyFields = document.getElementsByClassName('money-field');
var quantityFields = document.getElementsByClassName('quantity-field');
var globalCount = 0;


for (let i = 0; i < formSelects.length; i++) {
    formSelects[i].addEventListener('change', function () {
        if (formSelects[i].value === "invalid") {
            //hide row if invalid option is clicked
            hideNextRows(i)
        } else {
            if (i === 0) {
                // action for interaction with category row
                let categoryId = formSelects[i].value;
                getProductsList(categoryId)
            } else {
                // action for non category row interaction
                showNextRow(i)
            }
        }

    });
}

for (let i = 0; i < moneyFields.length; i++) {
    moneyFields[i].addEventListener('change', function () {
        let parent = confirmCheck.parentElement;
        let container = parent.parentElement;
        if ((moneyFields[i].value && quantityFields[i].value) &&
            (moneyFields[i].value != 0 && quantityFields[i].value != 0)) {
            container.classList.remove('hidden')
        } else {
            container.classList.add('hidden');
        }
    })

    quantityFields[i].addEventListener('change', function () {
        let parent = confirmCheck.parentElement;
        let container = parent.parentElement;
        if ((moneyFields[i].value && quantityFields[i].value) &&
            (moneyFields[i].value != 0 && quantityFields[i].value != 0)) {
            container.classList.remove('hidden')

        } else {
            container.classList.add('hidden');
        }
    })

}

confirmCheck.addEventListener('click', function () {
    if (confirmCheck.checked) {
        document.getElementById('submitBtn').setAttribute('type', 'submit');
        document.getElementsByClassName('modal-footer')[0].classList.remove('hidden')
    } else {
        document.getElementById('submitBtn').setAttribute('type', 'button');
        document.getElementsByClassName('modal-footer')[0].classList.add('hidden')
    }

})

function hideNextRows(index) {
    index++;
    let rows = document.getElementsByClassName('form-row');
    for (let i = index; i < rows.length; i++) {
        rows[i].classList.add('hidden');
    }
}

function showNextRow(index) {
    try {
        if (index === 2) {
            //hide anything no matter what is pressed. acts like a reset for next sibling.
            hideNextRows(index);
            //show sales row if sales is clicked
            document.getElementById('transactionTypeSelect').value == 1 ? index++ : '';
        }
        index++;
        let row = document.getElementsByClassName('form-row')[index];
        row.classList.remove('hidden');

    } catch (e) {

    }
}

function getProductsList(categoryId, selectedId = '') {
    $.ajax({
        type: 'GET',
        url: `/ajax/category/${categoryId}/products`,
        data: '_token = {{csrf_token()}}',
        success: function (data) {

            let result = data.msg;

            showNextRow(0)
            let productSelect = document.getElementById('productSelect');
            productSelect.innerHTML = '';

            makeSelectOption(result, selectedId);

        }
    });
}

function makeSelectOption(products, selectedId = '') {
    const productSelect = document.getElementById('productSelect');
    productSelect.innerHTML = `<option selected value='invalid'>Select product.</option>`;
    if (products.length === 0) {
        productSelect.innerHTML = productSelect.innerHTML + `<option value='invalid'>No product available.</option>`;
    }
    for (let i = 0; i < products.length; i++) {
        let option = document.createElement('option');
        option.classList.add('dynamic-product-option');
        option.setAttribute('value', products[i].id);
        option.textContent = products[i].name;
        productSelect.appendChild(option)
    }
    if (selectedId.length !== 0) {
        productSelect.value = selectedId;
    }
}

