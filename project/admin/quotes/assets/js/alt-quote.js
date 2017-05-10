$(function () {
    var $select_elem = $("#customer");
    var $product_selector = $('select[name=product]');
    var $submit_button = $('input[name="btnsubmit"]');
    var $form = $("#quoteform");

    var Customer = {
        product_id: null,
        initCustomer: function () {
            var url = SITE_PATH + '/ajax/admin/quotes/getcustomer';
            $select_elem.empty();
            $select_elem.select2({
                minimumInputLength: 2,
                theme: "bootstrap",
                placeholder: "Select a customer",
                allowClear: true,
                ajax: {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            query: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                }
            });
            $select_elem.on('select2:select', function () {
                Customer.selectCustomer();
            });
        },
        selectCustomer: function () {
            $data = $select_elem.select2('data')[0];
            $('input[name=email]').val($data.email);
            $('input[name=phone]').val($data.phone);
        }
    };
    var Product = {
        names: null,
        id: null,
        initListener: function () {
            $product_selector.on('change', function () {
                Product.setProductId(this.value);
            });
            $submit_button.on('click', function (e) {
                // stop default action
                e.preventDefault();
                if (!Product.names) {
                    alert("Select product please");
                }
                else {
                    var ref = SITE_PATH + "/admin/myquote/new/" + Product.names;
                    $form.attr("action", ref);
                    $form.submit();
                }
            });
        },
        setProductId: function (id) {
            Product.id = id;
            Product.name();
        },
        name: function () {
            if (!Product.id) {
                return false;
            }
            switch (Product.id) {
                case "1":
                    Product.names = "motor";
                    break;
                case "5":
                    Product.names = "accident";
                    break;
                case "7":
                    Product.names = "travel";
                    break;
                case  "8":
                    Product.names = "domestic";
                    break;
                case "9":
                    Product.names = "medical";
                    break;
            }
        }
    };
    Customer.initCustomer();
    Product.initListener();
});
