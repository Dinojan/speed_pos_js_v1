angularApp.controller("StockReportsController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "OrderPayModel", "OrderAddModel", "OrderEditModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, OrderPayModel, OrderAddModel, OrderEditModel,) {
        var dt = $("#stock-report");
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
        $scope.selectedStatus = "all";

        $scope.selectProductStatus = function (status) {
            $scope.selectedStatus = status;
            dt.DataTable().ajax.reload(null, false);
        };

        dt.DataTable({
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            fixedHeader: true,
            order: [[0, "asc"]],
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',
            columnDefs: [
                { targets: [10, 11], orderable: false },
                { className: "text-center", targets: [0, 1, 3, 4, 5, 8, 9, 10, 11] },
                { className: "pl-3", targets: [2, 6, 7] },
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
                data: function (d) {
                    d.action_type = "GET_STOCKS";
                    d.from = $scope.from;
                    d.to = $scope.to;
                    d.status = $scope.selectedStatus;
                },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "p_code" },
                { data: "p_name" },
                { data: "mat" },
                { data: "karate" },
                { data: "category" },
                { data: "supplier" },
                { data: "cost" },
                { data: "wgt" },
                { data: "qty" },
                { data: "sts" },
                { data: "view" }
            ],

            drawCallback: function () {
                $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
                $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
                $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
            }
        });
        $(document).delegate("#pay-order", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
                $scope.order = d;
                OrderPayModel($scope);
            }
        });

        $(document).delegate("#edit-order", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
                $scope.order = d;
                OrderEditModel($scope);
            }
        });
        $(document).delegate("#delete-order", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();

            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }

            if (d) {
                $scope.order = d;
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('action_type', 'DELETE');
                        formData.append('id', $scope.order.id);

                        $http({
                            url: window.baseUrl + "/_inc/_order.php",
                            method: "POST",
                            data: formData,
                            transformRequest: angular.identity,
                            headers: { 'Content-Type': undefined }
                        }).then(
                            function (response) {
                                var alertMsg = response.data.msg;
                                Swal.fire({
                                    title: "Deleted!",
                                    text: alertMsg,
                                    icon: "success"
                                });
                                $('.table').DataTable().ajax.reload(null, false);
                            },
                            function (response) {
                                var alertMsg = "";
                                angular.forEach(response.data, function (value) {
                                    alertMsg += value + " ";
                                });
                                Swal.fire({
                                    title: "Oops!",
                                    text: alertMsg,
                                    icon: "error"
                                });
                            }
                        );
                    }
                });
            }
        });



        // Optional: method to open modal
        $scope.openAddorderModal = function () {
            OrderAddModel($scope);
        };
    }
]);
