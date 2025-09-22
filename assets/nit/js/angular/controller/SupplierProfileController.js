angularApp.controller("SupplierProfileController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "SupplierAddModal", "SupplierEditModal", "SupplierDeleteModal", "ProductDetailsModel", "ProductEditModal",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, SupplierAddModal, SupplierEditModal, SupplierDeleteModal, ProductDetailsModel, ProductEditModal) {
        var dt = $("#supplier-jewels-list");
        var i;
        var hideColums = dt.data("hide-colums").split(",");
        var hideColumsArray = [];
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i += 1) {
                hideColumsArray.push(parseInt(hideColums[i]));
            }
        }
        // var isdeleted = window.getParameterByName('isdeleted');
        var sup = window.getParameterByName('supplier');
        dt.DataTable({
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            fixedHeader: true,
            order: [[0, "asc"]],
            dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',

            columnDefs: [
                { targets: [8, 9, 10], orderable: false },
                { className: "text-center", targets: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10] },
                { visible: false, targets: hideColumsArray },
            ],
            aLengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            ajax: {
                url: "../_inc/_product.php",
                type: "GET",
                data: { action_type: "GET_SUP_JEWELS", sup: sup },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "p_code" },
                { data: "p_name" },
                { data: "category" },
                { data: "supplier" },
                { data: "wgt" },
                { data: "cost" },
                { data: "sts" },
                { data: "view" },
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
        $(document).delegate("#edit-product", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            console.log("edit")
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
                $scope.product = d;
                ProductEditModal($scope);
            }
        });
        $(document).delegate("#delete-product", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            console.log("delete")
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }

            if (d) {
                $scope.Product = d;

                var text;
                var btnTxt;
                //console.log($scope.Product.status );
                if ($scope.Product.status == 2) {
                    text = "You need to restore this product!";
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
                        formData.append('id', $scope.Product.id);

                        $http({
                            url: window.baseUrl + "/_inc/_product.php",
                            method: "POST",
                            data: formData,
                            transformRequest: angular.identity,
                            headers: { 'Content-Type': undefined }
                        }).then(
                            function (response) {
                                var alertMsg = response.data.msg;
                                Swal.fire({
                                    title: ($scope.Product.status == 2) ? "Restored!" : "Deleted!",
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

        $(document).off("click", "#view-product-details").on("click", "#view-product-details", function (e) {
            e.preventDefault();
            console.log("view model")
            var id = $(this).data("id");
            ProductDetailsModel($scope, id);
        });
    }]);