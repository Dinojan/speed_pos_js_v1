angularApp.controller("SalesListController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce,) {
        var dt = $("#sales-list");
        var i;
        var hideColums = (dt.data("hide-colums") || "").toString().split(",");
        var hideColumsArray = [];

        $scope.from = window.getParameterByName('from');
        $scope.to = window.getParameterByName('to');
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i += 1) {
                hideColumsArray.push(parseInt(hideColums[i]));
            }
        }
        dt.DataTable({
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            fixedHeader: true,
            order: [[0, "asc"]],
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',

            columnDefs: [
                { targets: [12, 13, 14], orderable: false },
                { className: "text-center", targets: [0, 1, 2, 3, 10, 11, 12, 13, 14] },
                { className: "pl-3", targets: [4, 5, 6, 7, 8, 9] },
                {
                    className: "text-capitalize", targets: [10], render: function (data) {
                        if (!data) return '';
                        return data.toString().replace(/\b\w/g, l => l.toUpperCase());
                    }
                },
                { visible: false, targets: hideColumsArray },
            ],
            aLengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            ajax: {
                url: "../_inc/_pos.php",
                type: "GET",
                data: {
                    action_type: "GET_POS_ORDERS",
                    from: $scope.from,
                    to: $scope.to
                },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "created_at" },
                { data: "invoice_no" },
                { data: "ref_no" },
                { data: "customer" },
                { data: "sub_total" },
                { data: "discount" },
                { data: "total_payable" },
                { data: "total_paid" },
                { data: "outstanding" },
                { data: "payment_method" },
                { data: "biller" },
                { data: "pay" },
                { data: "view" },
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

        $(document).delegate("#edit-customer", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
                $scope.customer = d;
                CustomerEditModal($scope);
            }
        });

        $(document).delegate("#delete-customer", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();

            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }

            if (d) {
                $scope.Customer = d;

                var text;
                var btnTxt;
                //console.log($scope.Customer.status );
                if ($scope.Customer.status == 2) {
                    text = "You need to restore this customer!";
                    btnTxt = "Yes, Restore it!";
                } else {
                    text = "You won't be able to revert this!";
                    btnTxt = "Yes, Delete it!";
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: btnTxt,
                }).then((result) => {
                    if (result.isConfirmed) {
                        var formData = new FormData();
                        formData.append('action_type', 'DELETE');
                        formData.append('id', $scope.Customer.id);

                        $http({
                            url: window.baseUrl + "/_inc/_customer.php",
                            method: "POST",
                            data: formData,
                            transformRequest: angular.identity,
                            headers: { 'Content-Type': undefined }
                        }).then(
                            function (response) {
                                var alertMsg = response.data.msg;
                                Swal.fire({
                                    title: ($scope.Customer.status == 2) ? "Restored!" : "Deleted!",
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
        $scope.openAddCustomerModal = function () {
            CustomerAddModal($scope);
        };
    }
]);
