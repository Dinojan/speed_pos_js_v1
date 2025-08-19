angularApp.controller("LoginController", ["$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce) {

    $scope.loginData = {
        email: "",
        password: "",
        action_type: "LOGIN"
    };
    $(document).keypress(function (event) {
        if (event.which === 13) {
           $scope.login();
        }
    });
    $scope.login = function () {
        $http({
            method: "POST",
            url: 'index.php?action_type=LOGIN',
            data: $("#login-form").serialize(),
            dataType: "JSON"
        })
            .then(function (response) {
                Swal.fire("success!", response.data.msg, "success");
                    setTimeout(function () {
                        window.location = getParameterByName('redirect_to') && getParameterByName('redirect_to') !== "undefined" && getParameterByName('redirect_to') !== "null" ? getParameterByName('redirect_to') : window.baseUrl + "/" + window.adminDir + "/dashboard.php";
                    }, 1000);
            }, function (response) {
                Swal.fire("Oops!", response.data.errorMsg, "error");
            });
    };
}]);