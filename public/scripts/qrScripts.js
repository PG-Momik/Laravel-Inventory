var scanner = new Instascan.Scanner({
    video: document.getElementById('preview'),
    scanPeriod: 5,
    mirror: true
});
scanner.addListener('scan', function (content) {
    ajaxProduct(1);
});
Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        scanner.start(cameras[0]);
        $('[name="options"]').on('change', function () {
            if ($(this).val() == 1) {
                if (cameras[0] != "") {
                    scanner.start(cameras[0]);
                } else {
                    alert('No Front camera found!');
                }
            } else if ($(this).val() == 2) {
                if (cameras[1] != "") {
                    scanner.start(cameras[1]);
                } else {
                    alert('No Back camera found!');
                }
            }
        });
    } else {
        console.error('No cameras found.');
        alert('No cameras found.');
    }
}).catch(function (e) {
    console.error(e);
    alert(e);
});

function ajaxProduct(id) {
    console.log("starting ajax call");
    let url = `/products/details/${id}`
    $.ajax({
        url: url,
        success: function (result) {
            triggerTransactionModal(result)
        }

    });

}

function triggerTransactionModal(result) {
    document.getElementById('makeTransactionBtn').click();
    document.getElementById('categorySelect').value = result.category_id;
    showNextRow(0);
    getProductsList(result.category_id, result.category_id);
    showNextRow(1);
    scanner.stop(cameras[0]);
}
