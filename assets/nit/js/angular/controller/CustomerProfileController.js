window.angularApp.controller("CustomerProfileController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$http",
    function ($scope,
        API_URL,
        window,
        $,
        $http
    ) {
        "use strict";
        var customer = window.getParameterByName("customer");
        var order = null;

        if (window.getParameterByName("order") && window.getParameterByName("order") !== null) {
            order = window.getParameterByName("order");
        }

        var orderdt = $("#customer-order-list");
        var i;
        var hideColums = orderdt.data("hide-colums").split(",");
        var hideColumsArray = [];
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i += 1) {
                hideColumsArray.push(parseInt(hideColums[i]));
            }
        }
        
        function initCustomerTable(tableSelector, ajaxParams) {
            var dt = $(tableSelector);
            var hideColums = dt.data("hide-colums").split(",");
            var hideColumsArray = hideColums.map(function (c) { return parseInt(c); });

            var isdeleted = window.getParameterByName('isdeleted');

            dt.DataTable({
                processing: true,
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                fixedHeader: true,
                order: [[0, "asc"]],
                dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',
                columnDefs: [
                    { targets: [5, 6, 7, 8], orderable: false },
                    { className: "text-center", targets: [0, 1, 3, 4, 5, 6, 7, 8] },
                    { visible: false, targets: hideColumsArray }
                ],
                aLengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
                ajax: ajaxParams,
                aoColumns: [
                    { data: "row_index" },
                    { data: "c_name" },
                    { data: "c_mobile" },
                    { data: "c_address" },
                    { data: "total_due" },
                    { data: "pay" },
                    { data: "profile" },
                    { data: "edit" },
                    { data: "delete" }
                ],
                drawCallback: function () {
                    $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
                    $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
                    $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                    $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                    $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
                }
            });
        }

        // Initialize both tables
        initCustomerTable("#customer-order-list", {
            url: "../_inc/_customer.php",
            type: "GET",
            data: { action_type: "GET_TABLE_DATA", isdeleted: window.getParameterByName('isdeleted') },
            dataSrc: "data"
        });

        initCustomerTable("#customer-payment-list", {
            url: "../_inc/_customer.php?customer=" + window.getParameterByName("customer"),
            type: "GET",
            data: { action_type: "GET_TABLE_DATA", isdeleted: window.getParameterByName('isdeleted') },
            dataSrc: "data"
        });

    }]);