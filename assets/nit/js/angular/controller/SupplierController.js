angularApp.controller("SupplierController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "SupplierAddModal", "SupplierEditModal", "SupplierDeleteModal",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, SupplierAddModal, SupplierEditModal, SupplierDeleteModal) {
        var dt = $("#SupplierTable");
        var i;
        var hideColums = dt.data("hide-colums").split(",");
        var hideColumsArray = [];
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
                { targets: [3, 4, 5], orderable: false },
                { className: "text-center", targets: [0, 2, 3, 4, 5] },
                { visible: false, targets: hideColumsArray },
            ],
            aLengthMenu: [
                [10, 25, 50, 100, 200, -1],
                [10, 25, 50, 100, 200, "All"]
            ],
            ajax: {
                url: "../_inc/_supplier.php",
                type: "GET",
                data: { action_type: "GET_TABLE_DATA" },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "s_name" },
                { data: "s_mobile" },
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

        $(document).delegate("#edit-supplier", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
                $scope.supplier = d;
                SupplierEditModal($scope);
            }
        });
          $(document).delegate("#delete-supplier", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
           var table = dt.DataTable();
            var $row = $(this).closest("tr");
            var d = table.row($row).data();
            if (!d) {
                d = table.row($row.prev()).data();
            }
            if (d) {
            $scope.supplier = d;
            SupplierDeleteModal($scope);
            }
        });

        // $(document).delegate("#delete-supplier", "click", function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();

        //     var table = dt.DataTable();
        //     var $row = $(this).closest("tr");
        //     var d = table.row($row).data();
        //     if (!d) {
        //         d = table.row($row.prev()).data();
        //     }

        //     if (d) {
        //         $scope.Supplier = d;

        //         Swal.fire({
        //             title: "Are you sure?",
        //             text: "You won't be able to revert this!",
        //             icon: "warning",
        //             showCancelButton: true,
        //             confirmButtonColor: "#3085d6",
        //             cancelButtonColor: "#d33",
        //             confirmButtonText: "Yes, delete it!"
        //         }).then((result) => {
        //             if (result.isConfirmed) {
        //                 var formData = new FormData();
        //                 formData.append('action_type', 'DELETE');
        //                 formData.append('id', $scope.Supplier.id);

        //                 $http({
        //                     url: window.baseUrl + "/_inc/_Supplier.php",
        //                     method: "POST",
        //                     data: formData,
        //                     transformRequest: angular.identity,
        //                     headers: { 'Content-Type': undefined }
        //                 }).then(
        //                     function (response) {
        //                         var alertMsg = response.data.msg;
        //                         Swal.fire({
        //                             title: "Deleted!",
        //                             text: alertMsg,
        //                             icon: "success"
        //                         });
        //                         $('.table').DataTable().ajax.reload(null, false);
        //                     },
        //                     function (response) {
        //                         var alertMsg = "";
        //                         angular.forEach(response.data, function (value) {
        //                             alertMsg += value + " ";
        //                         });
        //                         Swal.fire({
        //                             title: "Oops!",
        //                             text: alertMsg,
        //                             icon: "error"
        //                         });
        //                     }
        //                 );
        //             }
        //         });
        //     }
        // });



        // Optional: method to open modal
        $scope.openAddSupplierModal = function () {
            SupplierAddModal($scope);
        };
    }
]);
