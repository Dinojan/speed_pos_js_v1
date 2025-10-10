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
        $scope.scannerActive = false;
        $scope.isScanning = false;
        $scope.currentFacingMode = 'environment';
        $scope.flashOn = false;
        $scope.cameraAttempts = 0;
        $scope.maxCameraAttempts = 3;

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


        // Scanner Functions
        $scope.startScanner = function () {

            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                const errorMsg = 'Camera API not supported in this browser. Please use Chrome, Firefox, or Safari.';
                $scope.showCameraError(errorMsg);
                return;
            }

            const scannerContainer = document.getElementById('scanner-container');
            const video = document.getElementById('scanner-video');

            if (!scannerContainer || !video) {
                return;
            }
            
            scannerContainer.style.display = 'flex';

            // const scannerOverlay = document.getElementById('scanner-overlay');
            // if (scannerOverlay) {
            //     scannerOverlay.classList.remove('scanning-animation');
            // }

            $scope.scannerActive = true;
            $scope.isScanning = true;
            $scope.cameraAttempts = 0;

            document.getElementById('camera-error').style.display = 'none';

            $scope.detectAndStartCamera();

        };

        $scope.detectAndStartCamera = function () {
            $scope.cameraAttempts++;

            const cameraConfigs = [
                { name: "Rear Camera", constraints: { video: { facingMode: "environment" } } },
                { name: "Front Camera", constraints: { video: { facingMode: "user" } } },
                { name: "Any Camera", constraints: { video: true } },
            ];

            const configIndex = Math.min($scope.cameraAttempts - 1, cameraConfigs.length - 1);
            const config = cameraConfigs[configIndex];

            navigator.mediaDevices.getUserMedia(config.constraints)
                .then(function (stream) {
                    $scope.handleCameraSuccess(stream);
                })
                .catch(function (error) {

                    if ($scope.cameraAttempts < 3 && configIndex < cameraConfigs.length - 1) {
                        setTimeout(() => $scope.detectAndStartCamera(), 500);
                    } else {
                        $scope.handleCameraError(error);
                    }
                });
        };

        $scope.handleCameraSuccess = function (stream) {
            const video = document.getElementById('scanner-video');
            if (!video) return;

            video.srcObject = stream;

            video.onloadedmetadata = function () {
                video.play().then(() => {

                    // Start both barcode and QR code scanners
                    $scope.startBarcodeScanner();
                    $scope.startQRCodeScanner();

                }).catch(err => {
                    $scope.showCameraError('Failed to start video playback: ' + err.message);
                });
            };

            video.onerror = function (err) {
                $scope.showCameraError('Video error occurred');
            };
        };

        // Barcode Scanner (QuaggaJS)
        $scope.startBarcodeScanner = function () {
            if (typeof Quagga === 'undefined') {
                return;
            }

            try {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector('#scanner-video'),
                        constraints: {
                            facingMode: $scope.currentFacingMode
                        }
                    },
                    decoder: {
                        readers: [
                            "code_128_reader",
                            "ean_reader",
                            "ean_8_reader",
                            "code_39_reader",
                            "code_39_vin_reader",
                            "codabar_reader",
                            "upc_reader",
                            "upc_e_reader",
                            "i2of5_reader",
                            "2of5_reader",
                            "code_93_reader"
                        ]
                    },
                    locator: {
                        patchSize: "medium",
                        halfSample: true
                    },
                    locate: true,
                    numOfWorkers: 2
                }, function (err) {
                    if (err) {
                        return;
                    }

                    Quagga.start();

                    Quagga.onDetected(function (result) {
                        if (result && result.codeResult && result.codeResult.code) {
                            const code = result.codeResult.code;
                            $scope.$apply(function () {
                                $scope.handleScannedCode(code, 'barcode');
                            });
                        }
                    });
                });
            } catch (error) {
                Toast.fire({
                    icon: 'Error',
                    title: "Opps!",
                    text: 'Error starting barcode scanner:' + error
                })
            }
        };

        // QR Code Scanner (jsQR)
        $scope.startQRCodeScanner = function () {
            if (typeof jsQR === 'undefined') {
                return;
            }
            $scope.qrScanningActive = true;
            $scope.scanQRCode();
        };

        $scope.scanQRCode = function () {
            if (!$scope.qrScanningActive || !$scope.isScanning) return;

            const video = document.getElementById('scanner-video');
            const canvas = document.getElementById('scanner-canvas');
            const context = canvas.getContext('2d');

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                // Set canvas dimensions to match video
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Draw video frame to canvas
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                // Get image data from canvas
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);

                // Scan for QR code
                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });

                if (code) {
                    $scope.$apply(function () {
                        $scope.handleScannedCode(code.data, 'qr_code');
                    });
                    return; // Stop scanning after detection
                }
            }

            // Continue scanning
            if ($scope.qrScanningActive && $scope.isScanning) {
                requestAnimationFrame(() => $scope.scanQRCode());
            }
        };

        $scope.handleScannedCode = function (code, type) {

            $scope.stopScanner();

            // Fill input with scanned code
            const searchInput = document.getElementById('serach_input');
            if (searchInput) {
                searchInput.value = code;
                searchInput.focus();
            }

            // Show success message with type
            if (typeof Toast !== 'undefined') {
                Toast.fire({
                    icon: 'success',
                    title: `${type === 'qr_code' ? 'QR Code' : 'Barcode'} Scanned!`,
                    text: 'Detected: ' + code
                });
            }

            // Auto-submit after a brief delay
            setTimeout(function () {
                $scope.searchItem();

                // Clear the input after submission to prepare for next scan
                setTimeout(function () {
                    if (searchInput) {
                        searchInput.value = '';
                    }
                }, 500);

            }, 1000);
            $scope.startScanner();
        };

        $scope.stopScanner = function () {
            $scope.isScanning = false;
            $scope.qrScanningActive = false;

            // Stop Quagga barcode scanner
            if (typeof Quagga !== 'undefined') {
                try {
                    Quagga.offDetected();
                    Quagga.stop();
                } catch (e) {
                    Toast.fire({
                        icon: 'Error',
                        title: "Opps!",
                        text: 'Error stopping Quagga:' + e
                    })
                }
            }

            // Stop video stream
            const video = document.getElementById('scanner-video');
            if (video && video.srcObject) {
                const tracks = video.srcObject.getTracks();
                tracks.forEach(track => {
                    track.stop();
                });
                video.srcObject = null;
            }

            const scannerContainer = document.getElementById('scanner-container');
            if (scannerContainer) {
                scannerContainer.style.display = 'none';
            }

            // const scannerOverlay = document.getElementById('scanner-overlay');
            // if (scannerOverlay) {
            //     scannerOverlay.classList.remove('scanning-animation');
            // }

            $scope.scannerActive = false;
            $scope.flashOn = false;

            document.getElementById('camera-error').style.display = 'none';
        };

        // $scope.switchCamera = function () {
        //     $scope.currentFacingMode = $scope.currentFacingMode === 'environment' ? 'user' : 'environment';
        //     $scope.stopScanner();
        //     setTimeout(() => {
        //         $scope.$apply(function () {
        //             $scope.startScanner();
        //         });
        //     }, 1000);
        // };

        // Check camera availability on page load
        $scope.checkCameraAvailability = function () {
            if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                Toast.fire({
                    icon: 'Error',
                    title: "Opps!",
                    text: 'Camera enumeration not supported'
                })
                return;
            }

            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');

                    if (videoDevices.length === 0) {
                        Toast.fire({
                            icon: 'Error',
                            title: "Opps!",
                            text: 'No cameras detected on this device'
                        })
                    }
                })
                .catch(err => {
                    Toast.fire({
                        icon: 'Error',
                        title: 'Opps!',
                        text: 'Error enumerating devices:' + err
                    })
                });
        };

        $scope.initScanner = function () {
            // Check camera availability
            $scope.checkCameraAvailability();

            $(document).on("click", "#btn-scan", function () {
                $scope.$apply(function () {
                    $scope.startScanner();
                });
            });

            $(document).on("click", "#scanner-close", function () {
                $scope.$apply(function () {
                    $scope.stopScanner();
                });
            });

            // $(document).on("click", "#switch-camera", function () {
            //     $scope.$apply(function () {
            //         $scope.switchCamera();
            //     });
            // });

            // $(document).on("click", "#scanner-container", function (e) {
            //     if (e.target.id === 'scanner-container') {
            //         $scope.$apply(function () {
            //             $scope.stopScanner();
            //         });
            //     }
            // });
        };


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

            const blockedKeys = [
                'NumLock', 'ScrollLock', 'PrintScreen', 'Pause', 'Insert',
                'Home', 'PageUp', 'PageDown', 'End',
                'ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight',
                'F1', 'F2', 'F3', 'F4', 'F5', 'F6', 'F7', 'F8', 'F9', 'F10', 'F11', 'F12'
            ];

            $(document).on('keydown', function (event) {
                const key = event.key;

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

        // 🔹 Initialize the application
        $scope.loadTableData();
        $scope.initScanner();
    }
]);