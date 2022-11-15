let typeSelect = document.getElementById('typeSelect');
typeSelect.addEventListener('change', function () {
    let currentUrl = window.location.href;
    currentUrl = currentUrl.split('/');

    if (currentUrl.length > max) {
        currentUrl = setToBaseURL(currentUrl);
    }
    if (currentUrl.length < max) {
        currentUrl.push(typeSelect.value)
    } else {
        currentUrl[max - 1] = typeSelect.value
    }
    currentUrl = extractQueryIfExists(currentUrl);

    let redirectURL = currentUrl.join('/');
    window.location.replace(redirectURL);
});
