function bsModal($scope) {
    const modalTemplate = `
  <div class="modal fade" id="${$scope.modalId}" tabindex="-1" role="dialog" aria-labelledby="${$scope.modalId}Label" aria-hidden="true">
    <div class="modal-dialog ${$scope.modalSize}" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white p-2">
          <h5 class="modal-title" id="${$scope.modalId}Label">${$scope.modalTitle}</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ${$scope.modalBody}
        </div>
      </div>
    </div>
  </div>
`;

    return modalTemplate;
}

angularApp.factory("userGroupAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "userGroupAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_user_group.php?action_type=ADD_USER_GROUP").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add User Group";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                });

            });
            $(document).off("click", "#create_user_group_submit").on("click", "#create_user_group_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_user_group.php",
                    method: "POST",
                    data: $('#create-user-group-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// UserGroupEditModal
angularApp.factory("UserGroupEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "UserGroupEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_user_group.php?action_type=EDIT_USER_GROUP&&id=" + $scope.userGroup.group_id).then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit User Group";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-lg";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                });

            });
            $(document).off("click", ".update_user_group_submit").on("click", ".update_user_group_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_user_group.php",
                    method: "POST",
                    data: $('#update-user-group-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// UserGroupDeleteModal
angularApp.factory("UserGroupDeleteModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "UserGroupDeleteModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_user_group.php?action_type=DELETE_USER_GROUP&&id=" + $scope.userGroup.group_id).then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Delete User Group";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-sm";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });

            });
            $(document).off("click", "#ugroup-delete").on("click", "#ugroup-delete", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_user_group.php",
                    method: "POST",
                    data: $('#ugroup-del-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);
                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// userAddModal
angularApp.factory("userAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "userAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_user.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add User";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $(document).off("click", "#create_user_submit").on("click", "#create_user_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_user.php",
                    method: "POST",
                    data: $('#create-user-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// UserEditModal
angularApp.factory("UserEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "UserEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_user.php?action_type=EDIT&id=" + $scope.user.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit User";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_user_submit").on("click", "#update_user_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_user.php",
                    method: "POST",
                    data: $('#update-user-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// categoryAddModal
angularApp.factory("categoryAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "categoryAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_category.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add Category";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $(document).off("click", "#create_category_submit").on("click", "#create_category_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_category.php",
                    method: "POST",
                    data: $('#create-category-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('#categoryTable').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// categoryEditModal
angularApp.factory("categoryEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "categoryEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_category.php?action_type=EDIT&id=" + $scope.category.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit Category";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_category_submit").on("click", "#update_category_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_category.php",
                    method: "POST",
                    data: $('#update-category-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('#categoryTable').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// categoryDeleteModal
angularApp.factory("categoryDeleteModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "categoryDeleteModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_category.php?action_type=DELETE&&id=" + $scope.category.id).then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Delete Category";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-sm";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });

            });
            $(document).off("click", "#category-delete").on("click", "#category-delete", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_category.php",
                    method: "POST",
                    data: $('#category-del-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('#categoryTable').DataTable().ajax.reload(null, false);
                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);

// SupplierAddModal
angularApp.factory("SupplierAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "SupplierAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_supplier.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add Supplier";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $(document).off("click", "#create_supplier_submit").on("click", "#create_supplier_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_supplier.php",
                    method: "POST",
                    data: $('#create-supplier-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        // if(window.current_nav == 'product'){
                        //     // add #s_id option
                        // }else{
                        $('.table').DataTable().ajax.reload(null, false);

                        // }

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// SupplierEditModal
angularApp.factory("SupplierEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "SupplierEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_supplier.php?action_type=EDIT&id=" + $scope.supplier.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit supplier";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_supplier_submit").on("click", "#update_supplier_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_supplier.php",
                    method: "POST",
                    data: $('#update-supplier-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// SupplierDeleteModal
angularApp.factory("SupplierDeleteModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "SupplierDeleteModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_supplier.php?action_type=DELETE&&id=" + $scope.supplier.id).then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Delete supplier";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-sm";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });

            });
            $(document).off("click", "#supplier-delete").on("click", "#supplier-delete", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_supplier.php",
                    method: "POST",
                    data: $('#supplier-del-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);
                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// ProductAddModal
angularApp.factory("ProductAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    "SupplierAddModal",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout, SupplierAddModal
    ) {
        return function ($scope) {
            const modalId = "ProductAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_product.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add Product";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $rootScope.suplierAddModal = function () {
                SupplierAddModal($scope);
            }
            $(document).off("click", "#create_product_submit").on("click", "#create_product_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_product.php",
                    method: "POST",
                    data: $('#create-product-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
//ProductEditModel
angularApp.factory("ProductEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "ProductEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_product.php?action_type=EDIT&id=" + $scope.product.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit product";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_product_submit").on("click", "#update_product_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_product.php",
                    method: "POST",
                    data: $('#update-product-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// CustomerAddModel
angularApp.factory("CustomerAddModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "CustomerAddModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_customer.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add Customer";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $rootScope.customerAddModal = function () {
                CustomerAddModal($scope);
            }
            $(document).off("click", "#create_customer_submit").on("click", "#create_customer_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_customer.php",
                    method: "POST",
                    data: $('#create-customer-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
//CustomerEditModel
angularApp.factory("CustomerEditModal", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "CustomerEditModal";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_customer.php?action_type=EDIT&id=" + $scope.customer.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit customer";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_customer_submit").on("click", "#update_customer_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_customer.php",
                    method: "POST",
                    data: $('#update-customer-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
// OrderAddModel
angularApp.factory("OrderAddModel", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "OrderAddModel";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_order.php?action_type=CREATE").then(function (response) {
                const formHtml = response.data;

                $(`#${modalId}`).remove();
                $scope.modalTitle = "Add Order";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });



            });
            $rootScope.orderAddModal = function () {
                OrderAddModal($scope);
            }
            // c_id
            $(document).off("change", "#c_id").on("change", "#c_id", function (e) {
                e.preventDefault();
                var c_id = $(this).val();
                $http({
                    url: window.baseUrl + "/_inc/_customer.php?action_type=GET_THE_CUSTOMER&c_id=" + c_id,
                    method: "GET",
                }).then(
                    function (response) {
                        $scope.customer = response.data.customer;
                        if ($scope.customer != null) {
                            $('#cus_name').val($scope.customer.c_name);
                            $('#cus_mobile').val($scope.customer.c_mobile);
                            $('#cus_address').val($scope.customer.c_address);
                        } else {
                            $('#cus_name').val('');
                            $('#cus_mobile').val('');
                            $('#cus_address').val('');
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });

            });
            $(document).off("click", "#create_order_submit").on("click", "#create_order_submit", function (e) {
                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_order.php",
                    method: "POST",
                    data: $('#create-order-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);
//OrderEditModel
angularApp.factory("OrderEditModel", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "OrderEditModel";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/_order.php?action_type=EDIT&id=" + $scope.order.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Edit order";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_order_submit").on("click", "#update_order_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_order.php",
                    method: "POST",
                    data: $('#update-order-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);

//OrderPayModel
angularApp.factory("OrderPayModel", [
    "API_URL",
    "window",
    "jQuery",
    "$http",
    "$sce",
    "$rootScope",
    "$compile",
    "$timeout",
    function (
        API_URL,
        window,
        $,
        $http,
        $sce,
        $rootScope,
        $compile,
        $timeout
    ) {
        return function ($scope) {
            const modalId = "OrderPayModel";
            // Step 1: Load modal form from server
            $http.get(window.baseUrl + "/_inc/template/order_pay_form.php?order_id=" + $scope.order.id).then(function (response) {
                const formHtml = response.data;
                $(`#${modalId}`).remove();
                $scope.modalTitle = "Pay order";
                $scope.modalId = modalId;
                $scope.modalSize = "modal-md";
                $scope.modalBody = $sce.trustAsHtml(formHtml);
                $scope.items = [];
                $scope.order = [];

                const modalTemplate = bsModal($scope);
                const modalElement = $compile(modalTemplate)($scope);
                angular.element("body").append(modalElement);
                $timeout(function () {
                    $scope.modalInstance = new bootstrap.Modal(document.getElementById(modalId));
                    $scope.modalInstance.show();
                    $('.select2').select2({
                        dropdownParent: $('#' + modalId).find('.modal-content')
                    });
                });
            });
            $(document).off("click", "#update_order_submit").on("click", "#update_order_submit", function (e) {

                e.preventDefault();

                var $tag = $(this);

                $http({
                    url: window.baseUrl + "/_inc/_order.php",
                    method: "POST",
                    data: $('#update-order-form').serialize(),
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "json"
                }).then(
                    function (response) {
                        var alertMsg = response.data.msg;
                        Toast.fire({ icon: 'success', title: 'Success!', text: alertMsg });
                        $('.table').DataTable().ajax.reload(null, false);

                        if ($scope.modalInstance) {
                            $scope.modalInstance.hide();
                        }

                    }, function (response) {
                        var alertMsg = "";
                        window.angular.forEach(response.data, function (value) {
                            alertMsg += value + " ";
                        });
                        ;
                        //window.toastr.warning(alertMsg, "Warning!");
                        Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
                    });
            });
        };
    }
]);