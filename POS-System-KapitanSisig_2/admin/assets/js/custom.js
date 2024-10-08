$(document).ready(function(){

    // Set alertify notification position
    alertify.set('notifier', 'position', 'top-right');

    // Increment Product Quantity
$(document).on('click', '.prod-increment', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var productId  = $(this).closest('.qtyBox').find('.prodId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue)){
        var qtyVal = currentValue + 1;
        $quantityInput.val(qtyVal);
        quantityIncDec(productId, qtyVal);
    }
});

// Decrement Product Quantity
$(document).on('click', '.prod-decrement', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var productId  = $(this).closest('.qtyBox').find('.prodId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue) && currentValue > 1){
        var qtyVal = currentValue - 1;
        $quantityInput.val(qtyVal);
        quantityIncDec(productId, qtyVal);
    }
});


    // Quantity Increment/Decrement with AJAX
    function quantityIncDec(prodId, qty) {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'productIncDec': true,
                'product_id': prodId,
                'quantity': qty
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    $('#productArea').load(' #productContent');
                    alertify.success(res.message);
                } else if (res.status == 500) {
                    alertify.error(res.message);
                    var $quantityInput = $('.qtyBox').find('.prodId[value="' + prodId + '"]').closest('.qtyBox').find('.prod-increment');
                    $quantityInput.prop('disabled', true);
                }
            }
        });
    }

    // Proceed to Place Order
    $(document).on('click', '.proceedToPlace', function(){
        var cname = $('#cname').val();
        var payment_mode = $('#payment_mode').val();

        // Validate Payment Method and Customer Name
        if (payment_mode === '') {
            swal("Select Payment Method", "Select your payment method", "warning");
            return false;
        }
        if (cname === '') {
            swal("Enter customer name", "Enter valid customer name", "warning");
            return false;
        }

        // Place Order via AJAX
        var data = {
            'proceedToPlaceBtn': true,
            'cname': cname,
            'payment_mode': payment_mode,
        };

        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: data,
            success: function(response){
                var res = JSON.parse(response);
                if (res.status == 200) {
                    window.location.href = "order-summary.php";
                } else if (res.status == 404) {
                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: { text: "Add Customer", value: "catch" },
                            cancel: "Cancel"
                        }
                    }).then((value) => {
                        if (value === "catch") {
                            $('#c_name').val(cname);
                            $('#addCustomerModal').modal('show');
                        }
                    });
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function() {
                swal('Error', 'Failed to process the request', 'error');
            }
        });
    });

    // Save Customer
    $(document).on('click', '.saveCustomer', function() {
        var c_name = $('#c_name').val();

        if (c_name !== '') {
            var data = {
                'saveCustomerBtn': true,
                'name': c_name
            };

            $.ajax({
                type: "POST",
                url: "orders-code.php",
                data: data,
                success: function(response){
                    var res = JSON.parse(response);
                    swal(res.message, res.message, res.status_type);
                    if (res.status == 200) {
                        $('#addCustomerModal').modal('hide');
                    }
                }
            });
        } else {
            swal("Please fill required fields", "", "warning");
        }
    });

    // Save Order
    $(document).on('click', '#saveOrder', function() {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: { 'saveOrder': true },
            success: function(response){
                var res = JSON.parse(response);
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function() {
                swal("Error", "Failed to process order", "error");
            }
        });
    });

    // Print Billing Area
    function printMyBillingArea() {
        var divContents = document.getElementById("myBillingArea").innerHTML;
        var a = window.open('', '');
        a.document.write('<html><title>Kapitan Sisig</title>');
        a.document.write('<body style="font-family: fangsong;">' + divContents + '</body></html>');
        a.document.close();
        a.print();
    }

    // Download PDF
    window.jsPDF = window.jspdf.jsPDF;
    var docPDF = new jsPDF();

    function downloadPDF(invoiceNo) {
        var elementHTML = document.querySelector("#myBillingArea");
        docPDF.html(elementHTML, {
            callback: function() {
                docPDF.save(invoiceNo + '.pdf');
            },
            x: 15,
            y: 15,
            width: 170,
            windowWidth: 650
        });
    }

    // Increment Ingredient Quantity
$(document).on('click', '.ing-increment', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue)){
        var qtyVal = currentValue + 1;
        $quantityInput.val(qtyVal);
        ingredientIncDec(ingredientId, qtyVal);
    }
});

// Decrement Ingredient Quantity
$(document).on('click', '.ing-decrement', function(){
    var $quantityInput = $(this).closest('.qtyBox').find('.qty');
    var ingredientId = $(this).closest('.qtyBox').find('.ingId').val();
    var currentValue = parseInt($quantityInput.val());

    if(!isNaN(currentValue) && currentValue > 1){
        var qtyVal = currentValue - 1;
        $quantityInput.val(qtyVal);
        ingredientIncDec(ingredientId, qtyVal);
    }
});


    // Ingredient Increment/Decrement AJAX
    function ingredientIncDec(ingId, qty) {
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: {
                'ingredientIncDec': true,
                'ingredient_id': ingId,
                'quantity': qty
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.status == 200) {
                    $('#ingredientArea').load(' #ingredientContent');
                    alertify.success(res.message);
                } else if (res.status == 500) {
                    alertify.error(res.message);
                    $('.ing-increment').prop('disabled', true);
                }
            }
        });
    }

    // Proceed to Place Ingredient Order
    $(document).on('click', '.proceedToPlaceIng', function() {
        var adminName = $('#adminName').val();
        var ingPayment_mode = $('#ingPayment_mode').val();
        var supplierName = $('#supplierName').val();

        // Validate Form Fields
        if (!ingPayment_mode || !supplierName || !adminName) {
            swal("Complete Form", "Please fill in all required fields", "warning");
            return false;
        }

        var data = {
            'proceedToPlaceIng': true,
            'adminName': adminName,
            'ingPayment_mode': ingPayment_mode,
            'supplierName': supplierName
        };

        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: data,
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    window.location.href = "purchase-order-summary.php";
                } else {
                    swal(response.message, response.message, response.status_type);
                }
            },
            error: function() {
                swal('Error', 'Failed to process the request', 'error');
            }
        });
    });

    // Save Purchase Order
    $(document).on('click', '#savePurchaseOrder', function() {
        $.ajax({
            type: "POST",
            url: "purchase-orders-code.php",
            data: { 'savePurchaseOrder': true },
            success: function(response){
                var res = JSON.parse(response);
                if (res.status == 200) {
                    swal(res.message, res.message, res.status_type);
                    $('#orderPlaceSuccessMessage').text(res.message);
                    $('#orderSuccessModal').modal('show');
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function() {
                swal("Error", "Failed to process order", "error");
            }
        });
    });

});
