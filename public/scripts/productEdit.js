
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
