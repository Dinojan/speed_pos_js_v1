angularApp.controller("ProductController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "ProductAddModal", "ProductEditModal", "ProductDetailsModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, ProductAddModal, ProductEditModal, ProductDetailsModel) {//
        var dt = $("#ProductTable");
        var i;
        var hideColums = dt.data("hide-colums").split(",");
        var hideColumsArray = [];
        if (hideColums.length) {
            for (i = 0; i < hideColums.length; i += 1) {
                hideColumsArray.push(parseInt(hideColums[i]));
            }
        }
        var isdeleted = window.getParameterByName('isdeleted');
        dt.DataTable({
            oLanguage: { sProcessing: "<img src='../assets/nit/img/loading.gif' >" },
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: '<"row"<"col-lg-4"l><"col-lg-4 text-center"B><"col-lg-4"f>>rt<"row"<"col-lg-6"i><"col-lg-6 text-right"p>>',
            fixedHeader: true,
            order: [[0, "asc"]],
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
                data: { action_type: "GET_TABLE_DATA", isdeleted: isdeleted },
                dataSrc: "data"
            },
            aoColumns: [
                { data: "row_index" },
                { data: "p_code" },
                { data: "p_name" },
                { data: "category" },
                // { data: "supplier" },
                { data: "wgt" },
                { data: "cost" },
                { data: "note" },
                { data: "sts" },
                { data: "view" },
                { data: "edit" },
                { data: "delete" }
            ],
            buttons: [
                {
                    extend: "print", footer: 'true',
                    text: "<i class=\"fa fa-print\"></i>",
                    titleAttr: "Print",
                    title: "Product Report",
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                            .css('margin', 'minimum !important')
                            .append(
                                '<div><b><i>Powered by: ' + window.copyright + '</i></b></div>'
                            )
                            .prepend(
                                '<div class="dt-print-heading"><img class="logo" src="' + window.logo + '"/><h2 class="title">' + window.store_name + '</h2><p>Printed on: ' + window.formatDate(new Date()) + '</p></div>'
                            );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: "copyHtml5",
                    text: "<i class=\"fas fa-copy\"></i>",
                    titleAttr: "Copy",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: "excelHtml5",
                    text: "<i class=\"fa fa-file-excel\"></i>",
                    titleAttr: "Excel",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: "csvHtml5",
                    text: "<i class=\"fas fa-file-csv\"></i>",
                    titleAttr: "CSV",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: "pdfHtml5",
                    text: "<i class=\"fa fa-file-pdf\"></i>",
                    titleAttr: "PDF",
                    download: "open",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 10; doc.styles.tableHeader.alignment = "center"; doc.styles.tableHeader.fillColor  = "#3882ed"
                        doc.styles.title.fontSize = 12;
                        // Remove spaces around page title
                        doc.content[0].text = doc.content[0].text.trim();
                        // Header
                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            fontSize: 8,
                            text: 'Created on: ' + window.formatDate(new Date()),
                        });
                        // Create a footer
                        doc['footer'] = (function (page, pages) {
                            return {
                                columns: [
                                    'Created by ' + window.copyright,
                                    {
                                        // This is the right column
                                        alignment: 'right',
                                        text: ['page ', { text: page.toString() }, ' of ', { text: pages.toString() }]
                                    }
                                ],
                                margin: [10, 0]
                            };
                        });
                        // Styling the table: create style object
                        var objLayout = {};
                        // Horizontal line thickness
                        objLayout['hLineWidth'] = function (i) { return 0.5; };
                        // Vertikal line thickness
                        objLayout['vLineWidth'] = function (i) { return 0.5; };
                        // Horizontal line color
                        objLayout['hLineColor'] = function (i) { return '#aaa'; };
                        // Vertical line color
                        objLayout['vLineColor'] = function (i) { return '#aaa'; };
                        // Left padding of the cell
                        objLayout['paddingLeft'] = function (i) { return 4; };
                        // Right padding of the cell
                        objLayout['paddingRight'] = function (i) { return 4; };
                        // Alignment of the cell
                        objLayout['alignment']=  function (i) { return 'center'; }
                        // Inject the object in the document
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
        });
        $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
        $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
        $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
        $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
        $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');

        $(document).delegate("#edit-product", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
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



        // Optional: method to open modal
        $scope.openAddProductModal = function () {
            ProductAddModal($scope);
        };

        $(document).off("click", "#view-product-details").on("click", "#view-product-details", function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            ProductDetailsModel($scope, id);
        });
    }
]);
