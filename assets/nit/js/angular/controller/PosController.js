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

        $scope.addToCart = function (product) {
            console.log(product)
            let exists = $scope.cart.find(item => item.serial === product.serial);
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
            return (item.price * item.qty) - item.discount;
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

        // $(document).off("click", "#create_customer_submit").on("click", "#create_customer_submit", function (e) {
        //     e.preventDefault();

        //     var formData = $('#create-customer-form').serialize();

        //     $http({
        //         url: window.baseUrl + "/_inc/_customer.php",
        //         method: "POST",
        //         data: formData,
        //         headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        //     }).then(
        //         function (response) {
        //             var alertMsg = response.data.msg;
        //             Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });

        //             // புதிய customer details assign பண்ணு
        //             $scope.customer = response.data.customer;

        //             // Order form auto-fill
        //             if ($scope.customer) {
        //                 $('#cus_name').val($scope.customer.c_name);
        //                 $('#cus_mobile').val($scope.customer.c_mobile);
        //                 $('#cus_address').val($scope.customer.c_address);
        //             }

        //             // modal close
        //             if ($scope.modalInstance) {
        //                 $scope.modalInstance.hide();
        //             }

        //         }, function (response) {
        //             var alertMsg = "";
        //             window.angular.forEach(response.data, function (value) {
        //                 alertMsg += value + " ";
        //             });
        //             Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
        //         }
        //     );
        // });

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
    }]);
