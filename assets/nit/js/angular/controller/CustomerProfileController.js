window.angularApp.controller("CustomerProfileController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "OrderPayModel",
    "CustomerEditModal",
    function ($scope,
        API_URL,
        window,
        $,
        $http,
        OrderPayModel,
        CustomerEditModal
    ) {
        "use strict";

        // var dt = $("#customer-order-list");
        // var i;
        // var hideColums = orderdt.data("hide-colums").split(",");
        // var hideColumsArray = [];
        // if (hideColums.length) {
        //     for (i = 0; i < hideColums.length; i += 1) {
        //         hideColumsArray.push(parseInt(hideColums[i]));
        //     }
        // }

        function initCustomerTable(tableSelector, ajaxParams, columns, unorderable, textCenter) {
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
                    { targets: unorderable, orderable: false },
                    { className: "text-center", targets: textCenter },
                    { visible: false, targets: hideColumsArray }
                ],
                aLengthMenu: [
                    [10, 25, 50, 100, 200, -1],
                    [10, 25, 50, 100, 200, "All"]
                ],
                ajax: ajaxParams,
                aoColumns: columns,
                drawCallback: function () {
                    $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
                    $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
                    $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                    $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                    $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
                }
            });
        }

        var customerId = window.getParameterByName("customer");
        var isdeleted = window.getParameterByName("isdeleted");
        var order = window.getParameterByName("order");
        var filter = (order === "" || order === undefined || order === null) ? "all" : "order";
        var paymentUrl = "../_inc/_customer_profile.php?customer=" + customerId;
        if (filter === "order") paymentUrl += "&order=" + order;

        if (filter === "all") {
            initCustomerTable(
                "#customer-order-list",
                {
                    url: "../_inc/_customer_profile.php?customer=" + customerId,
                    type: "GET",
                    data: {
                        action_type: "GET_TABLE_DATA",
                        isdeleted: isdeleted,
                        filter: filter,
                        table: "order"
                    },
                    dataSrc: "data"
                },
                [
                    { data: "row_index" },
                    { data: "created_at" },
                    { data: "cus_name" },
                    { data: "ref_no" },
                    { data: "order_details" },
                    { data: "total_amt" },
                    { data: "outstanding" },
                    { data: "total_paid" },
                    { data: "view" },
                    { data: "pay" }
                ],
                [8, 9],
                [0, 1, 3, 6, 8, 9]
            );
        }

        initCustomerTable(
            "#customer-payment-list",
            {
                url: paymentUrl,
                type: "GET",
                data: {
                    action_type: "GET_TABLE_DATA",
                    isdeleted: isdeleted,
                    filter: filter,
                    table: "payment"
                },
                dataSrc: "data"
            },
            [
                { data: "row_index" },
                { data: "created_at" },
                { data: "order_no" },
                { data: "order_details" },
                { data: "note" },
                { data: "amount" },
                { data: "view" }
            ],
            [3, 5],
            [0, 1, 2, 3, 6]
        );

        if (filter === "order") {
            $("#order-list-container").hide();
        } else {
            $("#order-list-container").show();
        }

        $(document).on("click", "#pay-order-profile-table-btn", function (e) {
            e.stopPropagation();
            e.preventDefault();

            var table = $("#customer-order-list").DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) d = table.row($row.prev()).data();
            if (d) {
                $scope.$apply(function () {
                    $scope.order = d;
                    OrderPayModel($scope);
                });
            }
        });



        $(document).on("click", "#edit-customer-profile-btn", function (e) {
            e.preventDefault();

            $scope.$apply(function () {
                $scope.customer = {};
                $scope.customer.id = customerId;
                CustomerEditModal($scope);
            });
        });


    }]);