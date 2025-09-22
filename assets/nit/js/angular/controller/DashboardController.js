angularApp.controller("DashboardController", ["$scope", "$http", "API_URL", function ($scope, $http, API_URL) {

    $http({
        method: 'GET',
        url: window.baseUrl + '/_inc/_material_price.php',
        params: { action_type: "GET_MATERIAL_PRICES" },
        responseType: "json"
    }).then(
        function (response) {
            $scope.materials = response.data.data;
            $scope.lastMaterialPrices = angular.copy($scope.materials);
        },
        function (response) {
            var alertMsg = response.data && typeof response.data === 'object'
                ? Object.values(response.data).join(" ")
                : response.statusText || "Error fetching material prices";
            Toast.fire({ icon: 'error', title: 'Oops!', text: alertMsg });
        }
    )
    // Function to send changed prices to backend
    function sendMaterialPrices(formData) {
        $http({
            method: 'POST',
            url: window.baseUrl + '/_inc/_material_price.php?action_type=CREATE',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json"
        }).then(function (response) {
            Swal.fire({
                title: "Updated!",
                icon: "success",
                text: 'Material prices updated successfully.'
            });
            $scope.materials = response.data.data;
            $scope.lastMaterialPrices = angular.copy($scope.materials);
        }, function (error) {
            Toast.fire({ icon: 'error', title: 'Error!', text: 'Failed to update prices.' });
        });
    }

    $scope.updateMaterialPrice = function () {
        // Validate inputs individually
        if (!$scope.materials.gold22) return Toast.fire({ icon: 'error', title: 'Error!', text: 'Please enter 22k gold price' });
        if (!$scope.materials.gold24) return Toast.fire({ icon: 'error', title: 'Error!', text: 'Please enter 24k gold price' });
        if (!$scope.materials.silver) return Toast.fire({ icon: 'error', title: 'Error!', text: 'Please enter silver price' });

        // Prepare only changed prices
        let formData = {};
        let warnings = [];

        if ($scope.materials.gold22 !== $scope.lastMaterialPrices.gold22) {
            formData.gold22 = $scope.materials.gold22;
        } else {
            warnings.push("22k Gold");
        }

        if ($scope.materials.gold24 !== $scope.lastMaterialPrices.gold24) {
            formData.gold24 = $scope.materials.gold24;
        } else {
            warnings.push("24k Gold");
        }

        if ($scope.materials.silver !== $scope.lastMaterialPrices.silver) {
            formData.silver = $scope.materials.silver;
        } else {
            warnings.push("Silver");
        }

        // If all prices are unchanged, alert and stop
        if (Object.keys(formData).length === 0) {
            return Swal.fire("All entered prices are same as the last updated values. Nothing to update.");
        }
        console.log(warnings.length)

        // If some prices are unchanged, alert which ones will not be updated
        if (warnings.length > 0) {
            Swal.fire({
                title: "Note",
                text: warnings.join(", ") + ` ${warnings.length > 1 ? 'prices are' : 'price is'} same as last updated. Only changed prices will be updated.`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#218838",
                cancelButtonColor: "#d33",
                confirmButtonText: "Update!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Serialize only changed prices
                    let serializedData = $.param(formData).replace(/%2C/g, '').replace(/\.00/g, '');
                    // Send to backend
                    sendMaterialPrices(serializedData);
                }
            })
        } else {
            // Serialize only changed prices
            let serializedData = $.param(formData).replace(/%2C/g, '').replace(/\.00/g, '');
            // Send to backend
            sendMaterialPrices(serializedData);
        }

    }
}]);
