angularApp.controller("PosController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "categoryAddModal", "categoryEditModal", "categoryDeleteModal", "OrderAddModel", "CustomerSelectModal",

    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, categoryAddModal, categoryEditModal, categoryDeleteModal, OrderAddModel, CustomerSelectModal) {
        $scope.cart = [];
        $('.select2').select2();
        $scope.products = [];
        $scope.barcode = null;
        $scope.nameOrBarcode = null;

        $scope.get_all_product = function (c_id = null) {
            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: { action_type: "GET_POS_PRODUCT", c_id: c_id },
                responseType: "json",
            }).then(
                function (response) {
                    $scope.products = response.data.products;
                },
                function (response) {
                    var alertMsg = "";
                    if (response.data && typeof response.data === 'object') {
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                    } else {
                        alertMsg = response.statusText || "Error fetching products";
                    }
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };

        $scope.get_all_product();

        $('#categorySelect').on('change', function () {
            var c_id = $(this).val();
            $scope.get_all_product(c_id);
            $scope.$apply();
        });

        $scope.addToCart = function (product) {
            let exists = $scope.cart.find(item => item.id === product.id);
            if (exists) {
                exists.qty += 1;
            } else {
                let newItem = angular.copy(product);
                newItem.qty = 1;
                newItem.discount = 0;
                $scope.cart.push(newItem);
            }
        };

        $scope.removeItem = function (index) {
            $scope.cart.splice(index, 1);
        };

        $scope.getSubtotal = function (item) {
            return (item.material_price * item.qty * (item.wgt / 8)) - item.discount;
        };

        $scope.getTotal = function () {
            return $scope.cart.reduce((sum, item) => sum + $scope.getSubtotal(item), 0);
        };

        $scope.getTotalDiscount = function () {
            return $scope.cart.reduce((sum, item) => sum + (parseFloat(item.discount) || 0), 0);
        };

        $scope.getFinalAmount = function () {
            return $scope.getTotal() - $scope.getTotalDiscount();
        };

        $scope.showItems = false;

        $scope.toggleItems = function () {
            $scope.showItems = !$scope.showItems;
        };

        $scope.openCustomerModal = function () {
            CustomerSelectModal($scope)
        }

        $(document).on('change', '#c_id', function () {
            var selected = $(this).find(':selected');

            if (selected.val() !== "") {
                $('#c_name').val(selected.data('name'));
                $('#c_mobile').val(selected.data('mobile'));
                $('#c_address').val(selected.data('address'));
            } else {
                $('#c_name').val('');
                $('#c_mobile').val('');
                $('#c_address').val('');
            }
        });


        $scope.showPaymentProcess = false;

        $scope.openPaymentProcess = function () {
            $scope.showPaymentProcess = true;
            console.log('openPaymentProcess');
        };

        $scope.closePaymentProcess = function () {
            $scope.showPaymentProcess = false;
            console.log('closePaymentProcess');
        };

        $scope.paymentMethod = "Cash";
        $scope.selectPaymentMethod = function (method) {
            if (method === "card") {
                $scope.paymentMethod = "Card"
            } else if (method === "cheque") {
                $scope.paymentMethod = "Cheque"
            } else {
                $scope.paymentMethod = "Cash"
            }
        }

        $scope.searchOption = "barcode";

        $scope.toggleSearchOption = function () {
            $scope.searchOption = $scope.searchOption === "name" ? "barcode" : "name";
        };

        $scope.getProductByBarcode = function () {
            $scope.barcode = $("#barcode").val();

            if (!$scope.barcode) return;

            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: {
                    action_type: "GET_POS_PRODUCT",
                    barcode: $scope.barcode
                },
                responseType: "json"
            }).then(
                function (response) {
                    $scope.products = response.data.products;
                    if ($scope.products.length === 1) {
                        $scope.addToCart($scope.products[0]);
                        $("#barcode").val('');
                        $scope.barcode = '';
                    }
                },
                function (response) {
                    var alertMsg = "";
                    if (response.data && typeof response.data === 'object') {
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                    } else {
                        alertMsg = response.statusText || "Error fetching products";
                    }
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };
        
        $scope.getProductByNameOrBarcode = function () {
            $scope.nameOrBarcode = $("#name-or-barcode").val();
            
            if (!$scope.nameOrBarcode) return;
            
            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: {
                    action_type: "GET_POS_PRODUCT",
                    nameOrBarcode: $scope.nameOrBarcode
                },
                responseType: "json"
            }).then(
                function (response) {
                    console.log($scope.products)
                    console.log("success")
                    $scope.products = response.data.products;
                    console.log($scope.products)
                    if ($scope.products.length === 1) {
                        $scope.addToCart($scope.products[0]);
                        $("#name-or-barcode").val('');
                        $scope.nameOrBarcode = '';
                    }
                },
                function (response) {
                    console.log("error")
                    var alertMsg = "";
                    if (response.data && typeof response.data === 'object') {
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                    } else {
                        alertMsg = response.statusText || "Error fetching products";
                    }
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };
    }]);
