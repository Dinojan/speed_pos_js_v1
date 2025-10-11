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

        // Add selected products array to scope
        $scope.selectedProducts = [];
        $scope.allProductsData = []; // Store all products data for confirmation display

        // Check if we should show checkbox column (only when isdeleted=2)
        var showCheckboxColumn = isdeleted === '2';

        var columnDefs = [
            { targets: [0, 8, 9, 10], orderable: false },
            { className: "text-center", targets: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10] },
            { visible: false, targets: hideColumsArray },
        ];

        var columns = [];

        if (showCheckboxColumn) {
            columns.push({
                data: null,
                render: function (data, type, row) {
                    return '<input type="checkbox" class="product-checkbox" data-id="' + row.id + '">';
                }
            });
            columnDefs[1].targets = [0, 1, 2, 4, 5, 6, 7, 8, 9, 10, 11];
        }

        // Add remaining columns
        columns.push(
            { data: "row_index" },
            { data: "p_code" },
            { data: "p_name" },
            { data: "category" },
            { data: "wgt" },
            { data: "cost" },
            { data: "note" },
            { data: "sts" },
            { data: "view" },
            { data: "edit" },
            { data: "delete" }
        );

        dt.DataTable({
            oLanguage: { sProcessing: "<img src='../assets/nit/img/loading.gif' >" },
            processing: true,
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            dom: showCheckboxColumn ?
                '<"row"<"col-lg-3"l><"col-lg-5 text-center"B><"col-lg-4"f>>rt<"row"<"col-lg-6"i><"col-lg-6 text-right"p>>' :
                '<"row"<"col-lg-4"l><"col-lg-4 text-center"B><"col-lg-4"f>>rt<"row"<"col-lg-6"i><"col-lg-6 text-right"p>>',
            fixedHeader: true,
            order: [[showCheckboxColumn ? 2 : 1, "asc"]], // Adjust order column based on checkbox presence
            columnDefs: columnDefs,
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
            aoColumns: columns,
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
                        columns: getExportColumns()
                    }
                },
                {
                    extend: "copyHtml5",
                    text: "<i class=\"fas fa-copy\"></i>",
                    titleAttr: "Copy",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: getExportColumns()
                    }
                },
                {
                    extend: "excelHtml5",
                    text: "<i class=\"fa fa-file-excel\"></i>",
                    titleAttr: "Excel",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: getExportColumns()
                    }
                },
                {
                    extend: "csvHtml5",
                    text: "<i class=\"fas fa-file-csv\"></i>",
                    titleAttr: "CSV",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: getExportColumns()
                    }
                },
                {
                    extend: "pdfHtml5",
                    text: "<i class=\"fa fa-file-pdf\"></i>",
                    titleAttr: "PDF",
                    download: "open",
                    title: window.store_name + " > Product Report",
                    exportOptions: {
                        columns: getExportColumns()
                    },
                    customize: function (doc) {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.pageMargins = [10, 10, 10, 10];
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 10; doc.styles.tableHeader.alignment = "center"; doc.styles.tableHeader.fillColor = "#3882ed"
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
                        objLayout['alignment'] = function (i) { return 'center'; }
                        // Inject the object in the document
                        doc.content[1].layout = objLayout;
                    }
                }
            ],
            initComplete: function () {
                // Add "Select All" checkbox in header if checkbox column is shown
                if (showCheckboxColumn) {
                    var api = this.api();
                    var headerCheckbox = $('<input type="checkbox" id="select-all-checkbox">');
                    var headerCustomCheckbox = $('<div class="custom-checkbox select-all" title="Select All"></div>');
                    headerCustomCheckbox.html('<i class="far fa-circle text-secondary"></i>');

                    // Add header for checkbox column
                    $(api.column(0).header()).html('')
                        .append(headerCheckbox)
                        .append(headerCustomCheckbox)
                        .css('text-align', 'center');

                    headerCheckbox.hide();

                    // Select All functionality
                    headerCustomCheckbox.on('click', function () {
                        var isAllSelected = headerCustomCheckbox.hasClass('checked');
                        var newState = !isAllSelected;

                        // Update header checkbox
                        headerCustomCheckbox.toggleClass('checked', newState);
                        headerCustomCheckbox.toggleClass('unchecked', !newState);
                        headerCustomCheckbox.html(newState ?
                            '<i class="fas fa-check-circle text-success"></i>' :
                            '<i class="far fa-circle text-secondary"></i>'
                        );
                        headerCheckbox.prop('checked', newState);

                        // Update all row checkboxes
                        api.rows({ page: 'current' }).every(function () {
                            var rowData = this.data();
                            var row = $(this.node());
                            var customCheckbox = row.find('.custom-checkbox:not(.select-all)');
                            var checkbox = row.find('.product-checkbox');

                            rowData.isSelected = newState;
                            customCheckbox.toggleClass('checked', newState);
                            customCheckbox.toggleClass('unchecked', !newState);
                            customCheckbox.html(newState ?
                                '<i class="fas fa-check-circle text-success"></i>' :
                                '<i class="far fa-circle text-secondary"></i>'
                            );
                            checkbox.prop('checked', newState);

                            // Update selected products array
                            if (newState) {
                                if (!$scope.selectedProducts.includes(rowData.id)) {
                                    $scope.selectedProducts.push(rowData.id);
                                }
                            } else {
                                var index = $scope.selectedProducts.indexOf(rowData.id);
                                if (index > -1) {
                                    $scope.selectedProducts.splice(index, 1);
                                }
                            }
                        });

                        $scope.$apply();
                    });
                }
            },
            createdRow: function (row, data, dataIndex) {
                // Store product data for confirmation display
                $scope.allProductsData[data.id] = data;

                // Add custom checkbox styling only if checkbox column is shown
                if (showCheckboxColumn) {
                    var checkbox = $(row).find('.product-checkbox');
                    checkbox.hide();

                    // Create custom checkbox display
                    var customCheckbox = $('<div class="custom-checkbox"></div>');
                    customCheckbox.addClass(data.isSelected ? 'checked' : 'unchecked');
                    customCheckbox.html(data.isSelected ?
                        '<i class="fas fa-check-circle text-success"></i>' :
                        '<i class="far fa-circle text-secondary"></i>'
                    );

                    checkbox.after(customCheckbox);

                    // Handle click on custom checkbox
                    customCheckbox.on('click', function () {
                        var isChecked = !data.isSelected;
                        data.isSelected = isChecked;

                        // Update visual state
                        customCheckbox.toggleClass('checked', isChecked);
                        customCheckbox.toggleClass('unchecked', !isChecked);
                        customCheckbox.html(isChecked ?
                            '<i class="fas fa-check-circle text-success"></i>' :
                            '<i class="far fa-circle text-secondary"></i>'
                        );

                        // Update actual checkbox
                        checkbox.prop('checked', isChecked);

                        // Update selected products array
                        if (isChecked) {
                            if (!$scope.selectedProducts.includes(data.id)) {
                                $scope.selectedProducts.push(data.id);
                            }
                        } else {
                            var index = $scope.selectedProducts.indexOf(data.id);
                            if (index > -1) {
                                $scope.selectedProducts.splice(index, 1);
                            }
                        }

                        // Update select all checkbox state
                        updateSelectAllCheckbox();

                        $scope.$apply();
                    });
                }
            }
        });

        // Helper function to get export columns based on checkbox visibility
        function getExportColumns() {
            var baseColumns = [0, 1, 2, 3, 4, 5, 6, 7]; // Row number + data columns
            if (showCheckboxColumn) {
                // Skip checkbox column (index 1) when exporting
                return baseColumns.map(function (col) {
                    return col === 0 ? 0 : col + 1;
                });
            }
            return baseColumns;
        }

        // Function to update select all checkbox state
        function updateSelectAllCheckbox() {
            if (!showCheckboxColumn) return;

            var api = dt.DataTable();
            var totalRows = api.rows({ page: 'current' }).count();
            var selectedRows = $scope.selectedProducts.length;

            var headerCustomCheckbox = $('.custom-checkbox.select-all');
            var headerCheckbox = $('#select-all-checkbox');

            if (selectedRows === 0) {
                headerCustomCheckbox.removeClass('checked').addClass('unchecked')
                    .html('<i class="far fa-circle text-secondary"></i>');
                headerCheckbox.prop('checked', false);
            } else if (selectedRows === totalRows) {
                headerCustomCheckbox.removeClass('unchecked').addClass('checked')
                    .html('<i class="fas fa-check-circle text-success"></i>');
                headerCheckbox.prop('checked', true);
            } else {
                // Indeterminate state
                headerCustomCheckbox.removeClass('checked unchecked')
                    .html('<i class="fas fa-minus-circle text-warning"></i>');
                headerCheckbox.prop('checked', false);
            }
        }

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

        $scope.openDeletePopover = function () {
            if ($scope.selectedProducts.length === 0) {
                Swal.fire({
                    title: "No products selected",
                    text: "Please select at least one product to delete.",
                    icon: "warning"
                });
                return;
            }

            // Get selected products details
            var selectedProductsDetails = $scope.selectedProducts.map(function (id) {
                return $scope.allProductsData[id];
            }).filter(function (product) {
                return product !== undefined;
            });

            // Generate product list HTML
            var productsListHtml = '<div id="swal-products-list" class="selected-products-list" style="max-height: 300px; overflow-y: auto; margin: 15px 0; border: 1px solid #e0e0e0; border-radius: 5px; padding: 10px;">';
            selectedProductsDetails.forEach(function (product, index) {
                productsListHtml += `
                    <div class="product-item" style="display: flex; align-items: center; justify-content: space-between; padding: 8px; border-bottom: ${index < selectedProductsDetails.length - 1 ? '1px solid #f0f0f0' : 'none'};">
                        <div style="flex: 1;">
                            <strong>${product.p_code}</strong> - ${product.p_name}
                            ${product.category ? '<br><small style="color: #666;">Category: ' + product.category + '</small>' : ''}
                        </div>
                        <div style="margin-left: 15px;">
                            <input type="checkbox" class="confirm-product-checkbox" data-id="${product.id}" checked style="cursor:pointer;">
                        </div>
                    </div>
                `;
            });
            productsListHtml += '</div>';
            productsListHtml += '<div style="font-size: 12px; color: #666; text-align: center; margin-top: 10px;">Uncheck any products you want to keep</div>';

            // SweetAlert confirmation
            Swal.fire({
                title: "Delete Selected Products?",
                html: `You are about to delete <strong>${$scope.selectedProducts.length}</strong> product(s).<br><br>${productsListHtml}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: `Delete ${$scope.selectedProducts.length} Product(s)`,
                cancelButtonText: "Cancel",
                width: '600px',
                didOpen: () => {
                    // Update confirm button dynamically when checkboxes are toggled
                    const checkboxes = document.querySelectorAll('.confirm-product-checkbox');
                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', () => {
                            const checkedCount = document.querySelectorAll('.confirm-product-checkbox:checked').length;
                            const confirmBtn = Swal.getConfirmButton();
                            confirmBtn.textContent = `Delete ${checkedCount} Product(s)`;
                        });
                    });
                },
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    // Only send checked IDs
                    const checkedIds = Array.from(
                        document.querySelectorAll('.confirm-product-checkbox:checked')
                    ).map(el => parseInt(el.getAttribute('data-id')));

                    if (checkedIds.length === 0) {
                        Swal.showValidationMessage('No products selected for deletion');
                        return false;
                    }

                    return $http({
                        url: window.baseUrl + "/_inc/_product.php",
                        method: "POST",
                        data: JSON.stringify({
                            action_type: 'DELETE_MULTIPLE',
                            ids: checkedIds
                        }),
                        headers: { 'Content-Type': 'application/json' }
                    }).then(
                        function (response) {
                            return response.data;
                        },
                        function (error) {
                            Swal.showValidationMessage('Request failed: ' + error.statusText);
                        }
                    );
                }
            }).then((result) => {
                if (result.isConfirmed && result.value.status === 'success') {
                    Swal.fire({
                        title: "Deleted!",
                        text: result.value.message,
                        icon: "success"
                    });

                    $scope.selectedProducts = [];
                    dt.DataTable().ajax.reload(null, false);
                    $scope.$apply();
                }
            });

        };


        // Function to toggle product selection from confirmation dialog
        $scope.toggleProductSelection = function (productId, isSelected) {
            if (isSelected) {
                if (!$scope.selectedProducts.includes(productId)) {
                    $scope.selectedProducts.push(productId);
                }
            } else {
                var index = $scope.selectedProducts.indexOf(productId);
                if (index > -1) {
                    $scope.selectedProducts.splice(index, 1);
                }
            }

            // Update the confirm button text
            var confirmButton = $('.swal2-confirm');
            if (confirmButton.length) {
                confirmButton.text(`Delete ${$scope.selectedProducts.length} Product(s)`);
            }
        };
    }
]);