var angularApp = angular.module("NITAgApp", ["ui.bootstrap"]);
angularApp.constant("API_URL", window.baseUrl);
angularApp.constant("window", window);
angularApp.constant("jQuery", window.jQuery);


angularApp.config(["$httpProvider", function ($httpProvider) {
    $httpProvider.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded; charset=UTF-8";
}]);

angularApp.run(["$rootScope", "API_URL", "window", function ($rootScope, API_URL, window) {
    $rootScope.API_URL = API_URL; //window.baseUrl; 
    $rootScope.window = window; 
}]);

angularApp.directive('bindHtmlCompile', ['$compile', function ($compile) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            scope.$watch(attrs.bindHtmlCompile, function (newValue) {
                if (newValue) {
                    // Inject the new HTML and compile it within the current scope
                    element.html(newValue);
                    $compile(element.contents())(scope);
                }
            });
        },
    };
}]);

angularApp.filter("cut", function () {
    return function (value, wordwise, max, tail) {
        if (!value) return "";
        max = parseInt(max, 10);
        if (!max) return value;
        if (value.length <= max) return value;
        value = value.substr(0, max);
        if (wordwise) {
            var lastspace = value.lastIndexOf(" ");
            if (lastspace !== -1) {

                //Also remove . and , so its gives a cleaner result.
                if (value.charAt(lastspace - 1) === "." || value.charAt(lastspace - 1) === ",") {
                    lastspace = lastspace - 1;
                }
                value = value.substr(0, lastspace);
            }
        }
        return value + (tail || " …");
    };
});

angularApp.filter('strReplace', function () {
    return function (input, from, to) {
        input = input || '';
        from = from || '';
        to = to || '';
        return input.replace(new RegExp(from, 'g'), to);
    };
});
angularApp.filter("formatDecimal", function () {
    return function (value, limit) {
        // if (!value) return "0.00";
        return window.formatDecimal(value, limit);
    };
});
angularApp.directive("filterList", function ($timeout) {
    return {
        link: function (scope, element, attrs) {
            var li = Array.prototype.slice.call(element[0].children);
            function filterBy(value) {
                li.forEach(function (el) {
                    el.className = el.textContent.toLowerCase().indexOf(value.toLowerCase()) !== -1 ? "" : "ng-hide";
                });
            }
            scope.$watch(attrs.filterList, function (newVal, oldVal) {
                if (newVal !== oldVal) {
                    filterBy(newVal);
                }
            });
        }
    };
});