angularApp.controller("PosController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "$timeout",
    "categoryAddModal", "categoryEditModal", "categoryDeleteModal", "OrderAddModel", "CustomerSelectModal", "OrderHoldingModel", "OrderHeldModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, $timeout, categoryAddModal, categoryEditModal, categoryDeleteModal, OrderAddModel, CustomerSelectModal, OrderHoldingModel, OrderHeldModel) {
        // Initialize scope variables
        $scope.cart = [];
        $scope.products = [];
        $scope.barcode = null;
        $scope.nameOrBarcode = null;
        $scope.paymentMethod = "Cash";
        $scope.showPaymentProcess = false;
        $scope.showItems = false;
        $scope.selectedCategory = null;
        $scope.selectedCustomer = null;
        $scope.searchOption = "barcode";
        $scope.payment = {
            advance: 0,
            received: 0,
            return: 0,
            sub_total: 0,
            total_discount: 0,
            final_payment: 0,
            balance: 0,
            outstanding: 0
        };
        $scope.cus = {
            id: '',
            name: '',
            address: '',
            mobile: ''
        };
        $scope.ref = $("#order-ref").val();
        // Initialize select2 safely
        if (typeof $.fn.select2 === 'function') {
            $('.select2').select2();
        } else {
            console.warn('Select2 library is not loaded');
        }

        // Fetch all products
        $scope.get_all_products = function (c_id = null) {
            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: { action_type: "GET_POS_PRODUCT", c_id: c_id },
                responseType: "json"
            }).then(
                function (response) {
                    $scope.products = response.data.products || [];
                },
                function (response) {
                    var alertMsg = response.data && typeof response.data === 'object'
                        ? Object.values(response.data).join(" ")
                        : response.statusText || "Error fetching products";
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };
        $scope.get_all_products();

        // $scope.get_all_held_orders_count = function () {
        //     $http({
        //         url: window.baseUrl + "/_inc/_pos.php",
        //         method: "GET",
        //         params: { action_type: "GET_HELD_ORDERS_COUNT" },
        //         responseType: "json"
        //     }).then(
        //         function (response) {
        //             $scope.held_orders_count = response.data.count || 0;
        //         },
        //         function (response) {
        //             var alertMsg = response.data && typeof response.data === 'object'
        //                 ? Object.values(response.data).join(" ")
        //                 : response.statusText || "Error fetching products";
        //             Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
        //         }
        //     );
        // };
        // $scope.get_all_held_orders_count();

        // Category change handler
        $scope.onCategoryChange = function () {
            $scope.get_all_products($scope.selectedCategory);
        };

        // Add item to cart
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
            $scope.updatePayment();
        };

        // Remove item from cart
        $scope.removeItem = function (index) {
            $scope.cart.splice(index, 1);
            $scope.updatePayment();
        };

        // Calculate subtotal for an item
        $scope.getSubtotal = function (item) {
            if (!item.material_price || !item.wgt || !item.qty) return 0;

            const subtotal = (item.material_price * item.qty * (item.wgt / 8)) - (parseFloat(item.discount) || 0);
            item.sub_total = subtotal;
            return subtotal;
        };


        $scope.getActualTotal = function (item) {
            if (!item.material_price || !item.wgt || !item.qty) return 0;
            return (item.material_price * item.qty * (item.wgt / 8));
        }

        // Calculate total
        $scope.getTotal = function () {
            return $scope.cart.reduce((sum, item) => sum + $scope.getActualTotal(item), 0);
        };

        // Calculate total discount
        $scope.getTotalDiscount = function () {
            return $scope.cart.reduce((sum, item) => sum + (parseFloat(item.discount) || 0), 0);
        };

        // Calculate final amount
        $scope.getFinalAmount = function () {
            return $scope.getTotal() - $scope.getTotalDiscount()
        };

        // Calculate total number of items
        $scope.getTotalItems = function () {
            let total = 0;
            angular.forEach($scope.cart, function (item) {
                total += item.qty || 0;
            });
            return total;
        };


        // Toggle items display
        $scope.toggleItems = function () {
            $scope.showItems = !$scope.showItems;
        };

        // Open customer modal
        $scope.openCustomerModal = function () {
            CustomerSelectModal($scope);
        };

        // Open order hold modal
        $scope.openOrderHoldingModal = function () {
            var hasProducts = $scope.cart.length > 0;

            if (hasProducts) {
                OrderHoldingModel($scope);
            } else {
                if (!hasProducts) {
                    Toast.fire({ icon: 'error', title: 'Error!', text: "Please select at least one product" });
                }
            }
        };

        // Open held orders modal
        // $scope.openHeldOrdersModal = function () {
        //     if ($scope.held_orders_count === 0) {
        //         Toast.fire({ icon: 'error', title: 'Error!', text: "No orders are on hold" });
        //     } else {
        //         OrderHeldModel($scope);
        //     }
        // };

        // Set default customer
        $scope.defaultCustomer = function () {
            $http({
                url: window.baseUrl + "/_inc/_customer.php?cus=last&action_type=DEFAULT_CUSTOMER",
                method: "GET",
                responseType: "json"
            }).then(
                function (response) {
                    const customer = response.data.customer;
                    $scope.cus = {
                        id: customer.id,
                        name: customer.c_name,
                        address: customer.c_address,
                        mobile: customer.c_mobile
                    };
                    $('#hidden_c_id').val(customer.id);
                    $('#hidden_c_name').val(customer.c_name);
                    $('#hidden_c_address').val(customer.c_address);
                    $('#hidden_c_mobile').val(customer.c_mobile);
                    if ($scope.modalInstance) $scope.modalInstance.hide();
                },
                function (response) {
                    var alertMsg = response.data && typeof response.data === 'object'
                        ? Object.values(response.data).join(" ")
                        : response.statusText || "Error fetching customer";
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };

        $scope.defaultCustomer();

        // Customer change handler
        $scope.onCustomerChange = function () {
            var selected = $('#c_id').find(':selected');
            if (selected.val()) {
                $scope.cus = {
                    id: selected.val(),
                    name: selected.data('name'),
                    address: selected.data('address'),
                    mobile: selected.data('mobile')
                };
                $('#c_name').val($scope.cus.name);
                $('#c_mobile').val($scope.cus.mobile);
                $('#c_address').val($scope.cus.address);
            } else {
                $scope.defaultCustomer();
            }
        };


        // Update payment calculations
        $scope.updatePayment = function () {
            const advance = parseFloat($scope.payment.advance) || 0;
            const received = parseFloat($scope.payment.received) || 0;
            const finalPayable = $scope.getFinalAmount() || 0;

            $scope.payment.sub_total = $scope.getTotal();
            $scope.payment.total_discount = $scope.getTotalDiscount();
            $scope.payment.final_payment = finalPayable;
            $scope.payment.balance = received - advance;
            $scope.payment.outstanding = finalPayable - advance;
            $scope.payment.return = received >= finalPayable ? received - finalPayable : 0;
        };

        // Watch cart and payment fields for changes
        $scope.$watch('[cart, payment.advance, payment.received, payment.total_discount]', $scope.updatePayment, true);

        // Open payment process
        $scope.openPaymentProcess = function () {
            var customerId = $scope.cus.id;
            var hasProducts = $scope.cart.length > 0;
            var refNo = $scope.ref;

            if (customerId && hasProducts && refNo) {
                $http({
                    url: window.baseUrl + "/_inc/_pos.php?ref=" + encodeURIComponent(refNo),
                    method: "GET"
                }).then(function (response) {
                    if (response.data.status === 'held' || response.data.status === 'exist') {
                        Toast.fire({ icon: 'error', title: 'Error', text: response.data.msg });
                    } else {
                        $scope.showPaymentProcess = true;
                        $scope.updatePayment();
                    }
                });
            } else {
                if (!customerId) {
                    Toast.fire({ icon: 'error', title: 'Error!', text: "Please select or add a customer" });
                }
                if (!hasProducts) {
                    Toast.fire({ icon: 'error', title: 'Error!', text: "Please select at least one product" });
                }
                if (!refNo) {
                    Toast.fire({ icon: 'error', title: 'Error!', text: "Please enter a bill or reference number" });
                }
            }
        };


        // Close payment process
        $scope.closePaymentProcess = function () {
            $scope.showPaymentProcess = false;
        };
        // Select payment method
        $scope.selectPaymentMethod = function (method) {
            $scope.paymentMethod = method.charAt(0).toUpperCase() + method.slice(1);
        };

        // Toggle search option
        $scope.toggleSearchOption = function () {
            $scope.searchOption = $scope.searchOption === "name" ? "barcode" : "name";
        };

        // Search by barcode
        $scope.getProductByBarcode = function () {
            $scope.barcode = $("#barcode").val();
            if (!$scope.barcode) return;

            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: { action_type: "GET_POS_PRODUCT", barcode: $scope.barcode },
                responseType: "json"
            }).then(
                function (response) {
                    $scope.products = response.data.products || [];
                    if ($scope.products.length === 1) {
                        $scope.addToCart($scope.products[0]);
                        $("#barcode").val('');
                        $scope.barcode = '';
                    }
                },
                function (response) {
                    var alertMsg = response.data && typeof response.data === 'object'
                        ? Object.values(response.data).join(" ")
                        : response.statusText || "Error fetching products";
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };

        // Search by name or barcode
        $scope.getProductByNameOrBarcode = function () {
            $scope.nameOrBarcode = $("#name-or-barcode").val();
            if (!$scope.nameOrBarcode) return;

            $http({
                url: window.baseUrl + "/_inc/_product.php",
                method: "GET",
                params: { action_type: "GET_POS_PRODUCT", nameOrBarcode: $scope.nameOrBarcode },
                responseType: "json"
            }).then(
                function (response) {
                    $scope.products = response.data.products || [];
                    if ($scope.products.length === 1) {
                        $scope.addToCart($scope.products[0]);
                        $("#name-or-barcode").val('');
                        $scope.nameOrBarcode = '';
                    }
                },
                function (response) {
                    var alertMsg = response.data && typeof response.data === 'object'
                        ? Object.values(response.data).join(" ")
                        : response.statusText || "Error fetching products";
                    Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                }
            );
        };

        // Digital keyboard setup
        $scope.activeInput = null;
        $scope.setActiveInput = function (field) {
            $scope.activeInput = field;
        };

        $scope.appendValue = function (val) {
            if ($scope.activeInput) {
                $scope.payment[$scope.activeInput] = ($scope.payment[$scope.activeInput] || "") + val;
                $scope.updatePayment();
            }
        };

        $scope.allowNumbersOnly = function (event) {
            var allowed = /[0-9.]/;
            if (!allowed.test(event.key)) {
                event.preventDefault();
            }
        };

        // Full amount button
        $scope.fullAmount = function () {
            $scope.payment.advance = $scope.payment.final_payment || 0;
            $scope.payment.received = $scope.payment.final_payment || 0;
            $scope.updatePayment();
        };

        // Clear payment button
        $scope.clearPayment = function () {
            $scope.payment.advance = 0;
            $scope.payment.received = 0;
            $scope.payment.total_discount = 0;
            $scope.updatePayment();
        };

        $scope.checkoutOrder = function () {
            if ($scope.payment.outstanding < 0) {
                Toast.fire({ icon: 'warning', title: 'Warning!', text: "Outstanding amount cannot be negative" });
                return;
            }
            $scope.cart.material_price = parseInt($scope.cart.material_price)
            // FormData create
            var formData = new FormData();
            formData.append('action_type', 'PLACE_ORDER');
            formData.append('customer', JSON.stringify($scope.cus));
            formData.append('paymentMethod', $scope.paymentMethod);
            formData.append('cart', JSON.stringify($scope.cart));
            formData.append('payment', JSON.stringify($scope.payment));
            formData.append('ref', $scope.ref);

            $http({
                url: window.baseUrl + "/_inc/_pos.php",
                method: "POST",
                data: formData,
                headers: { 'Content-Type': undefined },
                transformRequest: angular.identity
            }).then(
                function (response) {
                    Toast.fire({ icon: 'success', title: '"Order has been placed!', text: response.data.msg });

                    $scope.cart = [];
                    $scope.payment = {
                        advance: 0,
                        received: 0,
                        return: 0,
                        sub_total: 0,
                        total_discount: 0,
                        final_payment: 0,
                        balance: 0,
                        outstanding: 0
                    };
                    $scope.paymentMethod = "cash";
                    $scope.ref = "";

                    $scope.closePaymentProcess();
                    $scope.defaultCustomer();
                },
                function (error) {
                    var alertMsg = error.data && error.data.msg
                        ? error.data.msg
                        : "Failed to place the order";
                    Toast.fire({ icon: 'error', title: 'Error', text: alertMsg });
                }
            );
        };

        // Handle select2 change events to avoid digest errors
        if (typeof $.fn.select2 === 'function') {
            $('#categorySelect').on('select2:select', function (e) {
                $timeout(function () {
                    $scope.selectedCategory = e.target.value;
                    $scope.onCategoryChange();
                });
            });

            $('#c_id').on('select2:select', function (e) {
                $timeout(function () {
                    $scope.selectedCustomer = e.target.value;
                    $scope.onCustomerChange();
                });
            });
        }

        // Remove previous bindings to avoid multiple click logs
        $(document).off("click", ".category-select").on("click", ".category-select", function (e) {
            e.preventDefault();
            var id = $(this).find("li.category-select").last().data("cid");

            if (!id) {
                id = $(this).data("cid");
            }
            $scope.selectedCategory = id;
            $scope.get_all_products($scope.selectedCategory);

            console.log("First id: " + id);
            $(this).parents("ul.nav-treeview").show().parent("li").addClass("menu-open");
        });


        $("#sidebar-search-input").on("keyup", function () {
            let filter = $(this).val().toLowerCase();
            let firstMatch = null;

            $(".nav-sidebar li.nav-item a.nav-link").removeClass("active");

            if (filter === "") return;

            $(".nav-sidebar li.nav-item").each(function () {
                let text = $(this).text().toLowerCase();

                if (text.indexOf(filter) > -1) {
                    $(this).children("a.nav-link").addClass("active");

                    $(this).parents("ul.nav-treeview").show().parent("li").addClass("menu-open");

                    if (!firstMatch) {
                        firstMatch = $(this);
                    }
                }
            });

            if (firstMatch) {
                $(".nav-sidebar").animate({
                    scrollTop: $(".nav-sidebar").scrollTop() + firstMatch.position().top - 100
                }, 500);
            }
        });
    }]);