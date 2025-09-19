angularApp.controller("CheckStockController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "OrderPayModel", "OrderAddModel", "OrderEditModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, OrderPayModel, OrderAddModel, OrderEditModel,) {
        var dt = $("#checked-jewels-list");
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
                { targets: [3], orderable: false },
                { className: "text-center", targets: [0, 1, 4, 5] },
                { className: "pl-3", targets: [2, 3, 6] },
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
                    action_type: "GET_CHECKED_STOCKS",
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
                { data: "checked_count" },
                { data: "checker" }
            ],

            drawCallback: function () {
                $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
                $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
                $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
                $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
            }
        });

        $scope.searchItem = function () {
            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "POST",
                data: $('#stock-search').serialize(),
                cache: false,
                processData: false,
                contentType: false,
                dataType: "json"
            }).then(
                function (response) {
                    var alertMsg = response.data.msg;
                    if (response.data.status == 'warning') {
                        Toast.fire({ icon: 'warning', title: 'Warning!', text: alertMsg });
                    } else {

                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                    }
                    $('#serach_input').val('');
                    $('.table').DataTable().ajax.reload(null, false);
                }, function (response) {
                    var alertMsg = "";
                    window.angular.forEach(response.data, function (value) {
                        alertMsg += value + " ";
                    });
                    ;
                    //window.toastr.warning(alertMsg, "Warning!");
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                });
        }

        $(document).off("click", "#btn_check_stock").on("click", "#btn_check_stock", function (e) {
            e.preventDefault();
            $scope.searchItem();
        });


    }
]);
