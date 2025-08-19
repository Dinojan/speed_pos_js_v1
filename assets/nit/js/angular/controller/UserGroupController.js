angularApp.controller("UserGroupController", [
  "$scope", "API_URL", "window", "jQuery", "$compile", "$uibModal", "$http", "$sce", "userGroupAddModal", "UserGroupEditModal","UserGroupDeleteModal",
  function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, userGroupAddModal, UserGroupEditModal,UserGroupDeleteModal) {
    var dt = $("#userGroupTable");
    var i;
    var hideColums = dt.data("hide-colums").split(",");
    var hideColumsArray = [];
    if (hideColums.length) {
      for (i = 0; i < hideColums.length; i += 1) {
        hideColumsArray.push(parseInt(hideColums[i]));
      }
    }
    dt.DataTable({
      processing: true,
      responsive: true,
      lengthChange: true,
      autoWidth: false,
      fixedHeader: true,
      order: [[0, "asc"]],
      dom: '<"row mb-3"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 text-end"f>>rt<"row mt-3"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6 text-end"p>>',

      columnDefs: [
        { targets: [1, 2, 3, 4], orderable: false },
        { className: "text-center", targets: [0, 2, 3, 4] },
        { visible: false, targets: hideColumsArray },
      ],
      aLengthMenu: [
        [10, 25, 50, 100, 200, -1],
        [10, 25, 50, 100, 200, "All"]
      ],
      ajax: {
        url: "../_inc/_user_group.php",
        type: "GET",
        data: { action_type: "GET_TABLE_DATA" },
        dataSrc: "data"
      },
      aoColumns: [
        { data: "row_index" },
        { data: "g_name" },
        { data: "user_count" },
        { data: "edit" },
        { data: "delete" }
      ],

      drawCallback: function () {
       $('.buttons-print').removeClass('btn-secondary').addClass('btn-outline-primary btn-sm');
        $('.buttons-copy').removeClass('btn-secondary').addClass('btn-outline-dark btn-sm');
        $('.buttons-excel').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
        $('.buttons-csv').removeClass('btn-secondary').addClass('btn-outline-success btn-sm');
        $('.buttons-pdf').removeClass('btn-secondary').addClass('btn-outline-danger btn-sm');
      }
    });

    $(document).delegate("#edit-user-group", "click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      var table = dt.DataTable();
      var $row = $(this).closest("tr");
      var d = table.row($row).data();
      if (!d) {
        d = table.row($row.prev()).data();
      }
      if (d) {
        $scope.userGroup = d;
        UserGroupEditModal($scope);
      }
    });
    $(document).delegate("#delete-user-group", "click", function (e) {
      e.stopPropagation();
      e.preventDefault();
      var table = dt.DataTable();
      var $row = $(this).closest("tr");
      var d = table.row($row).data();
      if (!d) {
        d = table.row($row.prev()).data();
      }
      if (d) {
        $scope.userGroup = d;
        UserGroupDeleteModal($scope);
      }
    });


    // Optional: method to open modal
    $scope.openAddMobileModal = function () {
      userGroupAddModal($scope);
    };
  }
]);
