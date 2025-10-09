angularApp.controller("StockCheckingController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce) {
        $scope.totalItems = 0;
        $scope.TotalCheckedItems = 0;
        $scope.checkedItems = [];
        $scope.uncheckedItems = [];

        $scope.loadTableData = function () {
            $http({
                url: "../_inc/_stock_checking.php",
                method: "GET",
                params: { action_type: "GET_TABLE_DATA" },
                responseType: "json"
            }).then(function (response) {
                const allData = response.data.data || [];
                $scope.totalItems = allData.length;

                $scope.checkedItems = allData.filter(item => item.status === 1);
                $scope.uncheckedItems = allData.filter(item => item.status === 0);

                $scope.TotalCheckedItems = $scope.checkedItems.length;

                $scope.renderDynamicTables($scope.checkedItems, $scope.uncheckedItems);
            });
        };


        $scope.renderDynamicTables = function (checkedData, uncheckedData) {
            if ($.fn.DataTable.isDataTable("#slectedItems")) $("#slectedItems").DataTable().destroy();
            if ($.fn.DataTable.isDataTable("#checkedItems")) $("#checkedItems").DataTable().destroy();

            renderTable($("#slectedItems"), [6, 7], [0, 1, 3, 6, 7], [2, 4, 5], uncheckedData, [0, 1, 2, 3, 4, 5, 6, 7]);
            renderTable($("#checkedItems"), [6, 7], [0, 1, 3, 6, 7], [2, 4, 5], checkedData, [0, 1, 2, 3, 4, 5, 6, 7]);
        };

        function renderTable(tableElem, orderablefalse, textCenter, paddingLeft, dataSet, exportCols) {
            tableElem.DataTable({
                data: dataSet,
                processing: true,
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                dom: '<"row"<"col-lg-4"l><"col-lg-4 text-center"B><"col-lg-4"f>>rt<"row"<"col-lg-6"i><"col-lg-6 text-right"p>>',
                order: [[0, "asc"]],
                columnDefs: [
                    { targets: orderablefalse, orderable: false },
                    { className: "text-center", targets: textCenter },
                    { className: "pl-3", targets: paddingLeft }
                ],
                columns: [
                    { data: "row_index" },
                    { data: "p_code" },
                    { data: "p_name" },
                    { data: "wgt" },
                    { data: "cost" },
                    { data: "note" },
                    { data: "checked_status" },
                    { data: "item_status" }
                ],
                buttons: [
                    {
                        extend: "print",
                        footer: true,
                        text: "<i class='fa fa-print'></i>",
                        titleAttr: "Print",
                        title: "Product Report",
                        customize: function (win) {
                            $(win.document.body)
                                .css('font-size', '10pt')
                                .css('margin', 'minimum !important')
                                .append('<div><b><i>Powered by: ' + window.copyright + '</i></b></div>')
                                .prepend(
                                    '<div class="dt-print-heading">' +
                                    '<img class="logo" src="' + window.logo + '"/>' +
                                    '<h2 class="title">' + window.store_name + '</h2>' +
                                    '<p>Printed on: ' + window.formatDate(new Date()) + '</p>' +
                                    '</div>'
                                );
                        },
                        exportOptions: { columns: exportCols }
                    },
                    {
                        extend: "copyHtml5",
                        text: "<i class='fas fa-copy'></i>",
                        titleAttr: "Copy",
                        title: window.store_name + " > Product Report",
                        exportOptions: { columns: exportCols }
                    },
                    {
                        extend: "excelHtml5",
                        text: "<i class='fa fa-file-excel'></i>",
                        titleAttr: "Excel",
                        title: window.store_name + " > Product Report",
                        exportOptions: { columns: exportCols }
                    },
                    {
                        extend: "csvHtml5",
                        text: "<i class='fas fa-file-csv'></i>",
                        titleAttr: "CSV",
                        title: window.store_name + " > Product Report",
                        exportOptions: { columns: exportCols }
                    },
                    {
                        extend: "pdfHtml5",
                        text: "<i class='fa fa-file-pdf'></i>",
                        titleAttr: "PDF",
                        download: "open",
                        title: window.store_name + " > Product Report",
                        exportOptions: { columns: exportCols },
                        customize: function (doc) {
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            doc.pageMargins = [10, 10, 10, 10];
                            doc.defaultStyle.fontSize = 10;
                            doc.styles.tableHeader.fontSize = 10;
                            doc.styles.tableHeader.alignment = "center";
                            doc.styles.tableHeader.fillColor = "#3882ed";
                            doc.styles.title.fontSize = 12;
                            doc.content[0].text = doc.content[0].text.trim();
                            doc.content.splice(1, 0, {
                                margin: [0, 0, 0, 12],
                                alignment: 'center',
                                fontSize: 8,
                                text: 'Created on: ' + window.formatDate(new Date())
                            });
                            doc['footer'] = (function (page, pages) {
                                return {
                                    columns: [
                                        'Created by ' + window.copyright,
                                        {
                                            alignment: 'right',
                                            text: ['page ', { text: page.toString() }, ' of ', { text: pages.toString() }]
                                        }
                                    ],
                                    margin: [10, 0]
                                };
                            });
                        }
                    }
                ],
            });

            // Style buttons
            $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
            $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
            $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
            $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
            $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
        }

        // 🔹 Initial data load
        $scope.loadTableData();

        $scope.searchItem = function () {
            $http({
                url: window.baseUrl + "/_inc/_stock_checking.php",
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
                    $scope.loadTableData();
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

        $scope.clearSelectedItems = function () {
            let confirmText = "";
            const checkedLen = $scope.TotalCheckedItems;
            const selectedLen = $scope.totalItems;

            if (checkedLen === 0 && $scope.totalItems === $scope.uncheckedItems.length) {
                confirmText = "You haven't tested any jewelry.";
            } else if (checkedLen !== selectedLen) {
                confirmText = "You haven't checked out the entire jewelry yet.\nThere are still more jewels left for you to check out.";
            }

            const proceedClear = function () {
                // $scope.uncheckedItems = [];
                // $scope.checkedItems = [];
                // $scope.totalItems = 0;
                // $scope.TotalCheckedItems = 0;
                // $scope.renderDynamicTables($scope.checkedItems, $scope.uncheckedItems);

                $http({
                    method: 'POST',
                    url: '../_inc/_stock_checking.php?action_type=CLEAR_SELECTED_ITEMS'
                }).then(function (response) {
                    window.location.href = window.baseUrl + '/_admin/stock_assinging.php'
                });
            };

            if (confirmText) {
                Swal.fire({
                    title: "Are you sure?",
                    text: confirmText,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, clear!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        proceedClear();
                    }
                });
            } else {
                proceedClear();
            }
        };


        $(document).ready(function () {
            const inputElem = $('#serach_input');

            // const blockedChars = /["\/\*\+,\.`~!@#$%^&()={}[\];:'"|\\<>?]/;

            const blockedKeys = [
                'NumLock', 'ScrollLock', 'PrintScreen', 'Pause', 'Insert',
                'Home', 'PageUp', 'PageDown', 'End',
                'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
                'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12'
            ];

            $(document).on('keydown', function (event) {
                const key = event.key;

                // Block certain characters
                // if (blockedChars.test(key)) {
                //     event.preventDefault();
                //     return false;
                // }

                // Ignore blocked keys
                if (!blockedKeys.includes(key)) {
                    inputElem.focus();
                }

                // Handle Escape
                if (key === "Escape") {
                    $scope.$apply(function () {
                        $scope.clearSelectedItems();
                    });
                }

                // Handle Enter
                if (key === 'Enter') {
                    const value = inputElem.val().trim();
                    if (value) {
                        $('#btn_check_stock').trigger('click');
                    } else {
                        inputElem.focus();
                    }
                }
            });
        });
    }
]);
