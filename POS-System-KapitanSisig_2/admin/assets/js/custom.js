$(document).ready(function(){

    alertify.set('notifier','position', 'top-right');
    $(document).on('click', '.increment', function(){

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt($quantityInput.val());



        if(!isNaN(currentValue)){
            var qtyVal = currentValue + 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);
        }
    });

    $(document).on('click', '.decrement', function(){

        var $quantityInput = $(this).closest('.qtyBox').find('.qty');
        var productId  = $(this).closest('.qtyBox').find('.prodId').val();

        var currentValue = parseInt($quantityInput.val());

        if(!isNaN(currentValue) && currentValue > 1 ){
            var qtyVal = currentValue - 1;
            $quantityInput.val(qtyVal);
            quantityIncDec(productId, qtyVal);  
        }
    });

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
                // Disable the increment button
                var $quantityInput = $('.qtyBox').find('.prodId[value="' + prodId + '"]').closest('.qtyBox').find('.increment');
                $quantityInput.prop('disabled', true); // Disable increment button
            }
        }
    });
}


    //proceed to place order button click
    $(document).on('click', '.proceedToPlace', function(){

        var cname = $('#cname').val();
        var payment_mode = $('#payment_mode').val();
    
        // Validate Payment Method
        if(payment_mode == ''){
            swal("Select Payment Method", "Select your payment method", "warning");
            return false;
        }
    
        // Validate Customer Name
        if(cname == ''){
            swal("Enter customer name", "Enter valid customer name", "warning");
            return false;
        }
    
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
                var res;
                try {
                    res = JSON.parse(response); // Parse response manually, but it could be redundant
                } catch(e) {
                    console.error('Error parsing JSON', e);
                    return;
                }
            
                if (res.status == 200) {
                    window.location.href = "order-summary.php";
                } else if (res.status == 404) {
                    swal(res.message, res.message, res.status_type, {
                        buttons: {
                            catch: {
                                text: "Add Customer",
                                value: "catch"
                            },
                            cancel: "Cancel"
                        }
                    }).then((value) => {
                        switch (value) {
                            case "catch":
                                $('#c_name').val(cname);
                                $('#addCustomerModal').modal('show');
                                // console.log('Pop the customer add modal');
                                break;
                            default:
                        }
                    });
                } else {
                    swal(res.message, res.message, res.status_type);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error', textStatus, errorThrown);
                swal('Error', 'Failed to process the request', 'error');
            }
            
        });
    });

    //Add customers to customer table
    $(document).on('click', '.saveCustomer', function() {

        var c_name = $('#c_name').val();

        if(c_name != ''){
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

                    if(res.status == 200){
                        swal(res.message, res.message, res.status_type);
                        $('#addCustomerModal').modal('hide');
                    }else if(res.status == 422){
                        swal(res.message, res.message, res.status_type);
                    }else{
                        swal(res.message, res.message, res.status_type);
                    }
                }
            });
        }else{
            swal("Please fill required fields","","warning");
        }

    });

    $(document).on('click', '#saveOrder', function() {
        $.ajax({
            type: "POST",
            url: "orders-code.php",
            data: {
                'saveOrder': true   
            },
            success: function(response){
                try {
                    var res = JSON.parse(response);  // Parse JSON only if valid
                    if(res.status == 200){
                        swal(res.message, res.message, res.status_type);
                        $('#orderPlaceSuccessMessage').text(res.message);
                        $('#orderSuccessModal').modal('show');

                    }else{
                        swal(res.message, res.message, res.status_type);
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", response);
                    swal("Error", "Unexpected response from server", "error");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
                swal("Error", "Failed to process order", "error");
            }
        });
    });    

    function printMyBillingArea(){
        var divContents = document.getElementById("myBillingArea").innerHTML;
        var a = window.open('', '');
        a.document.write('<html><title>Kapitan Sisig</title>');
        a.document.write('<body style="font-family: fangsong;">');
        a.document.write(divContents);
        a.document.write('</body></html>');
        a.document.close;
        a.print();
    }

    
});

function printMyBillingArea(){
    var divContents = document.getElementById("myBillingArea").innerHTML;
    var a = window.open('', '');
    a.document.write('<html><title>Kapitan Sisig</title>');
    a.document.write('<body style="font-family: fangsong;">');
    a.document.write(divContents);
    a.document.write('</body></html>');
    a.document.close;
    a.print();
}

window.jsPDF = window.jspdf.jsPDF;
var docPDF = new jsPDF();

function downloadPDF(invoiceNo){
    var elementHTML = document.querySelector("#myBillingArea");
    docPDF.html( elementHTML, {
        callback: function() {
            docPDF.save(invoiceNo+'.pdf');
        },
        x: 15,
        y: 15,
        width: 170,
        windowWidth: 650
    });
}