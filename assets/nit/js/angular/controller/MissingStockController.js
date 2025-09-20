angularApp.controller("MissingStockController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "StockCheckingModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, StockCheckingModel) {
        var dt = $("#missing-stocks-list");
        var i;
        var hideColums = (dt.data("hide-colums") || "").toString().split(",");
        var hideColumsArray = [];
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i += 1) {
                hideColumsArray.push(parseInt(hideColums[i]));
            }
        }
        $scope.from = window.getParameterByName('from');
        $scope.to = window.getParameterByName('to');
        dt.DataTable({
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            fixedHeader: true,
            order: [[0, "asc"]],
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',
            columnDefs: [
                { targets: [3, 5], orderable: false },
                { className: "text-center", targets: [0, 1, 4, 5] },
                { className: "pl-3", targets: [2, 3] },
                // {
                //     className: "text-capitalize", targets: [10], render: function (data) {
                //         if (!data) return '';
                //         return data.toString().replace(/\b\w/g, l => l.toUpperCase());
                //     }
                // },
                { visible: false, targets: hideColumsArray },
            ],
            aLengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            ajax: {
                url: "../_inc/_product.php",
                type: "GET",
                data: {
                    action_type: "GET_MISSING_PRODUCTS",
                    from: $scope.from,
                    to: $scope.to
                },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "created_at" },
                { data: "p_code" },
                { data: "p_name" },
                { data: "sts" },
                { data: "view" },
            ],

            drawCallback: function () {
                $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
                $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
                $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
            }
        });


        $(document).off("click", "#view-checked-history").on("click", "#view-checked-history", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            StockCheckingModel($scope, id);
        });
    }
]);
