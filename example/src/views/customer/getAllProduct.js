// A $( document ).ready() block.
$( document ).ready(function() {
    window.onload = function() {
        list_products();
        list_category();
    };
});


function list_products() {
    $.getJSON("../../controllers/customer/product-controller.php", function (data) {
        var read_products_html = ``;
        for (var i in data) {
            read_products_html += `
                <div class="h-96">
                    <img src=` + data[i]["product_image"] +` alt="Product 1">
                    <p class="mt-2.5 text-base font-bold">`+ data[i]["name"] +`<p>
                    <p class="text-base text-gray-500">`+ data[i]["color_name"] +`</p>
                    <p>`+ data[i]["price"] +`VND</p>
                </div>
            `        
        }
        
        $("#page-content-product").html(read_products_html);
    });
}

function list_category() {
    $.getJSON("../../controllers/customer/category-controller.php", function (data) {
        var read_category_html = ``;
        for (var i in data) {
            read_category_html += `
                    <br>
                    <div> `+ data[i]["category_name"] +`</div>`        
        }
        
        $("#page-content-category").html(read_category_html);
    });
}

