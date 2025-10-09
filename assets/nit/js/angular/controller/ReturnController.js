angularApp.controller("ReturnController", [
    "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "$timeout",
    "categoryAddModal", "categoryEditModal", "categoryDeleteModal", "OrderAddModel", "CustomerSelectModal", "OrderHoldingModel", "OrderHeldModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, $timeout, categoryAddModal, categoryEditModal, categoryDeleteModal, OrderAddModel, CustomerSelectModal, OrderHoldingModel, OrderHeldModel) {
        // Initialize scope variables
        $scope.cart = [];
        $scope.products = [];
        $scope.barcode = null;
        $scope.nameOrBarcode = null;
        $scope.showItems = false;
        $scope.selectedCategory = null;
        $scope.searchOption = "barcode";
        $scope.ref = $("#order-ref").val();

        // Fetch all products
        $scope.get_all_products = function (c_id = null) {
            $http({
                url: window.baseUrl + "/_inc/_return.php",
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

        // Category change handler
        $scope.onCategoryChange = function () {
            $scope.get_all_products($scope.selectedCategory);
        };

        // Add item to cart
        $scope.addToCart = function (product) {
            let exists = $scope.cart.find(item => item.id === product.id);

            if (exists) {
                Toast.fire({ icon: 'error', title: 'Error!', text: "Product already exist" });
            } else {
                let newItem = {
                    id: product.id,
                    p_name: product.p_name,
                    p_code: product.p_code,
                    wgt: product.wgt,
                    material_price: parseFloat(product.sold_price) || 0
                };
                $scope.cart.push(newItem);
            }

            $scope.updatePayment();
        };

        // Remove item from cart
        $scope.removeItem = function (index) {
            $scope.cart.splice(index, 1);
            $scope.updatePayment();
        };

        // Calculate total
        $scope.getTotal = function () {
            let total = 0;
            $scope.cart.forEach(item => {
                total += item.material_price || 0;
            });
            return total;
        };

        $scope.getTotalItems = function () {
            return $scope.cart.length;
        };

        // Update payment calcilation
        $scope.updatePayment = function () {
            $scope.totalAmount = $scope.getTotal();
            $scope.totalItems = $scope.getTotalItems();
        };


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

        // Watch cart and payment fields for changes
        $scope.$watch('[cart, payment.advance, payment.received, payment.total_discount]', $scope.updatePayment, true);

        // Process button
        $scope.clickProcessBtn = function () {
            var hasProducts = $scope.cart.length > 0;
            var refNo = $scope.ref;

            if (hasProducts && refNo) {
                $http({
                    url: window.baseUrl + "/_inc/_return.php?action_type=PLACE_RETURN",
                    method: "POST",
                    data: {
                        ref: refNo,
                        cart: $scope.cart,
                    }
                }).then(function (response) {
                    Toast.fire({ icon: 'success', title: 'Success', text: response.data.msg ? response.data.msg : 'Product return successfully' });
                    $scope.cart = [];
                    $scope.ref = '';
                    $scope.updatePayment();

                    // Clear cart & ref
                    $scope.cart = [];
                    $scope.ref = '';
                    $scope.updatePayment();

                    // Reload products of current category
                    $scope.get_all_products($scope.selectedCategory);
                }, function (response) {
                    Toast.fire({ icon: 'error', title: 'Error', text: response.data.msg });
                });
            } else {
                if (!hasProducts) Toast.fire({ icon: 'error', title: 'Error!', text: "Please select at least one product" });
                if (!refNo) Toast.fire({ icon: 'error', title: 'Error!', text: "Please enter a bill or reference number" });
            }
        };

        // Cancel button - clear cart
        $scope.clickCancelBtn = function () {
            $scope.cart = [];
            $scope.updatePayment();
            $scope.ref = '';
            Toast.fire({ icon: 'info', title: 'Cancelled', text: 'Cart cleared' });
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
                        $scope.popoverData = $scope.products[0];

                        $("#barcode").val('');
                        $scope.barcode = '';

                        document.querySelector('[popovertarget="item-data"]').click();

                        // input auto focus
                        setTimeout(() => {
                            let input = document.querySelector('#item-data input[name="price"]');
                            if (input) input.focus();
                        }, 200);
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
                        $scope.popoverData = $scope.products[0];

                        $("#name-or-barcode").val('');
                        $scope.barcode = '';

                        document.querySelector('[popovertarget="item-data"]').click();
                        // input auto focus
                        setTimeout(() => {
                            let input = document.querySelector('#item-data input[name="price"]');
                            if (input) input.focus();
                        }, 200);
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


        // Remove previous bindings to avoid multiple click logs
        $(document).off("click", ".category-select").on("click", ".category-select", function (e) {
            e.preventDefault();
            var id = $(this).find("li.category-select").last().data("cid");

            if (!id) {
                id = $(this).data("cid");
            }
            $scope.selectedCategory = id;
            $scope.get_all_products($scope.selectedCategory);

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

        // Toggle items display
        $scope.toggleItems = function () {
            $scope.showItems = !$scope.showItems;
        };



        // Popover ----------------------------------------------------------------------------------------------------------------------------------------------------
        $scope.popoverData = {};

        $scope.showPopover = function (event) {
            var data = event.currentTarget.getAttribute('data-popover');
            $scope.popoverData = JSON.parse(data);
            if ($scope.popoverData.created_at || $scope.popoverData.sold_date) {
                var dateStr = $scope.popoverData.created_at;
                $scope.popoverData.created_at = new Date(dateStr.replace(' ', 'T'));
            }

            if ($scope.popoverData.sold_date) {
                var dateStr = $scope.popoverData.sold_date;
                $scope.popoverData.sold_at = new Date(dateStr.replace(' ', 'T'));
            }

            // input auto focus
            setTimeout(() => {
                let input = document.querySelector('#item-data input[name="price"]');
                if (input) input.focus();
            }, 200);
        };

        $(document).on('keydown', function (evt) {
            var tag = evt.target.tagName.toLowerCase();
            if (tag === 'input' || tag === 'textarea') return;

            if (evt.key === "Escape") {
                $scope.$apply(function () {
                    $scope.clickCancelBtn();
                });
            }

            if (evt.key === "Enter") {
                evt.preventDefault();

                let popover = document.getElementById('item-data');
                let isOpen = popover && popover.matches(':popover-open');

                $scope.$apply(function () {
                    if (!isOpen) {
                        // If popover is not open → open it for the selected product
                        let focusedBtn = document.activeElement;
                        if (focusedBtn && focusedBtn.hasAttribute('data-popover')) {
                            let data = focusedBtn.getAttribute('data-popover');
                            $scope.popoverData = JSON.parse(data);

                            focusedBtn.click(); // trigger the popover
                        }
                    } else {
                        // If popover is open → add to cart and close it
                        $scope.addToCart($scope.popoverData);
                        popover.hidePopover();
                    }
                });
                return;
            }

            if (evt.key === " " || evt.code === "Space") {
                evt.preventDefault();
                $scope.$apply(function () {
                    $scope.clickProcessBtn();
                });
            }

            let items = $('.item-btn').toArray();
            if (!items.length) return;

            let activeIndex = items.indexOf(document.activeElement);

            let columns = 4;
            if (window.innerWidth <= 576) columns = 2;
            else if (window.innerWidth <= 768) columns = 3;

            let nextIndex;

            switch (evt.key) {
                case 'ArrowRight':
                    evt.preventDefault();
                    nextIndex = activeIndex + 1;
                    if (nextIndex >= items.length) nextIndex = items.length - 1;
                    items[nextIndex].focus();
                    break;

                case 'ArrowLeft':
                    evt.preventDefault();
                    nextIndex = activeIndex - 1;
                    if (nextIndex < 0) nextIndex = 0;
                    items[nextIndex].focus();
                    break;

                case 'ArrowDown':
                    evt.preventDefault();
                    nextIndex = activeIndex + columns;
                    if (nextIndex >= items.length) nextIndex = items.length - 1;
                    items[nextIndex].focus();
                    break;

                case 'ArrowUp':
                    evt.preventDefault();
                    nextIndex = activeIndex - columns;
                    if (nextIndex < 0) nextIndex = 0;
                    items[nextIndex].focus();
                    break;
            }
        });

    }
]);