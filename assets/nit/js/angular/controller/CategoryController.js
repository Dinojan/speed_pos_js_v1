angularApp.controller("CategoryController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "categoryAddModal", "categoryEditModal","categoryDeleteModal",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, categoryAddModal, categoryEditModal,categoryDeleteModal) {

        // init DataTable for root table only
        var dt = $("#categoryTable");
        dt.DataTable({
            processing: true,
            responsive: false, // disable since tree is custom
            paging: false,     // no pagination (tree is expandable instead)
            searching: false,  // disable search if tree only
            ordering: false,   // ordering handled by hierarchy
            info: false,
            ajax: {
                url: "../_inc/_category.php",
                type: "GET",
                data: { action_type: "GET_TABLE_DATA" },
                dataSrc: "data"
            },
            columns: [
                { data: "c_name" }
            ],
            rowCallback: function (row, data, i) {
                renderCategoryRow($(row), data, i + 1);
            }
        });

        /**
         * Recursively render a category row (with children if any)
         */
        function renderCategoryRow($row, data) {
            var $td = $row.find("td").first();

            if (data.children && data.children.length > 0) {
                $td.html(`
            <div class="row w-100 align-items-center">
                <div class="col-lg-6"><b>${data.sl}</b> 
                    <span class="btn btn-sm btn-outline-primary expandable-table-caret">
                        <i class="fas fa-caret-right fa-fw"></i>
                    </span>
                    ${data.c_name}
                </div>
                <div class="col-lg-2">${data.wgt}g</div>
                <div class="col-lg-4 text-right">
                    ${data.view}
                    ${data.edit}
                    ${data.delete}
                </div>
            </div>
        `);

                var $childContainer = $(`
            <div class="child-container d-none pl-3 w-100">
                <table class="table w-100 table-hover mb-0 child-table">
                    <tbody></tbody>
                </table>
            </div>
        `);

                $td.append($childContainer);

                var $childTbody = $childContainer.find("tbody");

                data.children.forEach(function (child) {
                    var $tr = $("<tr><td></td></tr>");
                    $childTbody.append($tr);
                    renderCategoryRow($tr, child);
                });

                var $caret = $td.find(".expandable-table-caret").first();
                $caret.on("click", function (e) {
                    e.stopPropagation();
                    $childContainer.toggleClass("d-none");
                    $(this).find("i").toggleClass("fa-caret-right fa-caret-down");
                });

            } else {
                $td.html(`
            <div class="row w-100 align-items-center">
                <div class="col-lg-6"><b>${data.sl}</b> ${data.c_name}</div>
                <div class="col-lg-2">${data.wgt}g</div>
                <div class="col-lg-4 text-right">
                    ${data.view}
                    ${data.edit}
                    ${data.delete}
                </div>
            </div>
        `);
            }
        }


        /**
         * Open Add Category modal
         */
        $scope.openAddCategoryModal = function () {
            categoryAddModal($scope);
        };

        // btn-edit
        // $scope.edit= function(data){
        //     console.log(data);
        // }
        $(document).delegate(".btn-edit", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var id = $(this).data('id');
            $scope.category = {
                id: id
            };
            categoryEditModal($scope);
        });
         $(document).delegate(".btn-del", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            var id = $(this).data('id');
            $scope.category = {
                id: id
            };
            categoryDeleteModal($scope);
        });
    }
]);
