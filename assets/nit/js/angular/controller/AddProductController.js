angularApp.controller("AddProductController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    "categoryAddModal", "categoryEditModal", "categoryDeleteModal", "ProductAddModal", "ProductEditModal", "ProductDetailsModel",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce, categoryAddModal, categoryEditModal, categoryDeleteModal, ProductAddModal, ProductEditModal, ProductDetailsModel) {
        // Classic Palette
        // const levelColors = ["#3366CC", "#2E8B57", "#D4A5A5", "#FFBF00", "#F5F5DC", "#708090", "#9370DB", "#FFD700", "#C0C0C0", "#20B2AA"];

        // High-Contrast Palette  
        const levelColors = ["#4285F4", "#34A853", "#FF6B9D", "#FB8C00", "#AB47BC", "#3949AB", "#E53935", "#FFD700", "#BDBDBD", "#26C6DA"];

        // Modern Muted Palette
        // const levelColors = ["#6A8CAF", "#8A9B68", "#D4A5A5", "#CC7A6F", "#957D6F", "#6C7B8B", "#9A7FAE", "#D4AF37", "#D7CCC8", "#6AB0B4"];

        $scope.isFilter = false;
        $scope.categoryData = [];

        $scope.openAddProductModal = function (category, catPath, material) {
            // console.log(material)
            if (!category || !catPath || !material) return;

            let categoryName = category.c_name;
            let categoryId = category.id;

            $http.get(window.baseUrl + "/_inc/_product.php", {
                params: { action_type: "GET_NEW_REF_NO" }
            }).then(function (res) {
                let newProductCode = res.data.newProductCode;
                ProductAddModal($scope, { categoryPath: catPath, categoryName, productCode: newProductCode, id: categoryId, material });
            });
        };


        function shadeColor(color, percent) {
            // color in hex format: "#RRGGBB"
            let R = parseInt(color.substring(1, 3), 16);
            let G = parseInt(color.substring(3, 5), 16);
            let B = parseInt(color.substring(5, 7), 16);

            R = parseInt(R * (100 + percent) / 100);
            G = parseInt(G * (100 + percent) / 100);
            B = parseInt(B * (100 + percent) / 100);

            R = (R < 255) ? R : 255;
            G = (G < 255) ? G : 255;
            B = (B < 255) ? B : 255;

            const RR = ((R.toString(16).length == 1) ? "0" + R.toString(16) : R.toString(16));
            const GG = ((G.toString(16).length == 1) ? "0" + G.toString(16) : G.toString(16));
            const BB = ((B.toString(16).length == 1) ? "0" + B.toString(16) : B.toString(16));

            return "#" + RR + GG + BB;
        }

        // function renderCategoryRow(data, level) {
        //     const bgColor = levelColors[level % levelColors.length];
        //     const hoverColor = shadeColor(bgColor, -20);
        //     const cursorStyle = data.children && data.children.length > 0 ? "cursor:pointer;" : "";

        //     const html = `
        //         <div class="row w-100 align-items-center hover-wrapper px-2 my-1 rounded expandable-table-caret text-lg"
        //             style="background-color:${bgColor}66; ${cursorStyle}"
        //             onmouseenter="this.style.backgroundColor='${hoverColor}99'"
        //             onmouseleave="this.style.backgroundColor='${bgColor}66'">
        //             <div class="col-lg-6">
        //                 <div class="py-2">
        //                     <b>${data.sl}</b>
        //                     ${data.children && data.children.length > 0 ? `<span class="">
        //                         <i class="fas fa-caret-right fa-fw"></i>
        //                     </span>` : ''}
        //                     ${data.c_name}
        //                 </div>
        //             </div>
        //             <div class="col-lg-2">
        //                 <div class="py-2">(${data.pcs} pcs) ${data.wgt}g</div>
        //             </div>
        //             <div class="col-lg-4 text-right" style="height: 100%;">
        //                 ${data.add_new_cat}
        //                 ${data.add_new_item}
        //                 ${data.edit}
        //                 ${data.delete}
        //             </div>
        //         </div>
        //     `;
        //     return { html, bgColor };
        // }

        // function renderCategoryRow(data, level) {
        //     const bgColor = levelColors[level % levelColors.length];
        //     const nextColor = levelColors[(level + 1) % levelColors.length]; // 👈 pick next color for gradient
        //     const hoverColor = shadeColor(bgColor, -20);
        //     const cursorStyle = data.children && data.children.length > 0 ? "cursor:pointer;" : "";

        //     const gradientBg = `linear-gradient(90deg, ${bgColor}aa, ${nextColor}aa)`; // 👈 gradient background

        //     const html = `
        //         <div class="row w-100 align-items-center hover-wrapper px-2 my-1 rounded expandable-table-caret text-lg"
        //             style="background:${gradientBg}; ${cursorStyle};"
        //             onmouseenter="this.style.background='linear-gradient(90deg, ${shadeColor(bgColor, -20)}cc, ${shadeColor(nextColor, -20)}cc)'"
        //             onmouseleave="this.style.background='${gradientBg}'">
        //             <div class="col-lg-4">
        //                 <div class="py-2">
        //                     <b>${data.sl}</b>
        //                     ${data.children && data.children.length > 0 ? `<span class="">
        //                         <i class="fas fa-caret-right fa-fw"></i>
        //                     </span>` : ''}
        //                     ${data.c_name}
        //                 </div>
        //             </div>
        //             <div class="col-lg-3">
        //                 <div class="py-2">(${data.count} pcs) ${data.totalWeight}g</div>
        //             </div>
        //             <div class="col-lg-5 text-right" style="height: 100%;">
        //                 ${data.add_new_cat}
        //                 ${data.add_new_item}
        //                 ${data.edit}
        //                 ${data.delete}
        //             </div>
        //         </div>
        //     `;
        //     return { html, bgColor };
        // }

        // function buildTree($container, categories, $scope, $compile, level = 0) {
        //     categories.forEach(category => {
        //         const { html, bgColor } = renderCategoryRow(category, level);

        //         // const borderStyle = `border-left: ${level * 4}px solid ${shadeColor(bgColor, -40)};`;
        //         const borderStyle = `border-left: 2px solid ${shadeColor(bgColor, -40)};`;
        //         const $row = $('<div class="category-row"></div>').html(html);
        //         $container.append($row);
        //         $compile($row.contents())($scope);

        //         // Create container for child categories
        //         let $childContainer;
        //         if (category.children && category.children.length > 0) {
        //             $childContainer = $(`<div class="child-row pl-3 d-none" style="${borderStyle}"></div>`);
        //             $container.append($childContainer);
        //             buildTree($childContainer, category.children, $scope, $compile, level + 1);
        //         }

        //         // Create container for products
        //         let $productContainer;
        //         const tableBorderStyle = `1px solid ${shadeColor(bgColor, -40)};`;
        //         if (category.products && category.products.length > 0) {
        //             $productContainer = $(`<div class="product-container pl-4 d-none col-lg-6 mx-auto"></div>`);
        //             category.products.forEach(product => {
        //                 const today = new Date().toISOString().split('T')[0];
        //                 const createdDate = new Date(product.created_at).toISOString().split('T')[0];

        //                 const isNew = (createdDate === today) ? '<span class="badge badge-warning ml-2">: New</span>' : '';
        //                 const productHtml = `
        //                     <div class="row w-100 align-items-center px-2 mb-2 rounded-pill product-row" style="border-bottom: ${tableBorderStyle};">
        //                         <div class="col-7 col-md-8 font-weight-bold text-lg">${product.p_name} - ${product.p_code} - <b class="text-success text-lg mr-lg-5">${product.qty}</b></div>
        //                         <div class="col-5 col-md-4 text-right">${isNew} ${product.delete}</div>
        //                     </div>`;
        //                 $productContainer.append(productHtml);
        //             });
        //             $container.append($productContainer);
        //         }

        //         // Expand/collapse click
        //         $row.find('.expandable-table-caret').on('click', function (e) {
        //             e.stopPropagation();
        //             if ($childContainer) $childContainer.toggleClass('d-none');
        //             if ($productContainer) $productContainer.toggleClass('d-none');
        //             $(this).find('i').toggleClass('fa-caret-right fa-caret-down');
        //         });
        //     });
        // }
        function renderCategoryRow(data, level) {
            const bgColor = levelColors[level % levelColors.length];
            const nextColor = levelColors[(level + 1) % levelColors.length];
            const hoverColor = shadeColor(bgColor, -20);
            const cursorStyle = data.children && data.children.length > 0 ? "cursor:pointer;" : "";
            const gradientBg = `linear-gradient(90deg, ${bgColor}aa, ${nextColor}aa)`;

            const html = `
                <div class="row w-100 align-items-center hover-wrapper px-2 my-1 rounded expandable-table-caret text-lg"
                    style="background:${gradientBg}; ${cursorStyle};"
                    onmouseenter="this.style.background='linear-gradient(90deg, ${shadeColor(bgColor, -20)}cc, ${shadeColor(nextColor, -20)}cc)'"
                    onmouseleave="this.style.background='${gradientBg}'">
                    <div class="col-lg-4">
                        <div class="py-2">
                            <b>${data.sl}</b>
                            ${data.children && data.children.length > 0 ? `<span><i class="fas fa-caret-right fa-fw"></i></span>` : ''}
                            ${data.c_name}
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="py-2">
                            (<span class="cat-count">${data.count}</span> pcs)
                            <span class="cat-weight">${(data.totalWeight || 0).toFixed(2)}</span>g
                        </div>
                    </div>

                    <div class="col-lg-5 text-right" style="height: 100%;">
                        ${data.add_new_cat}
                        ${data.add_new_item}
                        ${data.edit}
                        ${data.delete}
                    </div>
                </div>
            `;
            return { html, bgColor };
        }


        $scope.calculateProductCount = function (category, filter) {
            let totalPcs = 0;
            let totalWgt = 0;

            // Count current category products
            if (category.products) {
                category.products.forEach(product => {
                    const today = new Date().toISOString().split('T')[0];
                    const createdDate = new Date(product.created_at).toISOString().split('T')[0];

                    if (product.status === 0 && (!filter || createdDate === today)) {
                        totalPcs += parseFloat(product.qty) || 0;
                        totalWgt += (parseFloat(product.wgt) || 0) * (parseFloat(product.qty) || 0);
                    }
                });
            }

            // Recursively count child categories
            if (category.children && category.children.length > 0) {
                category.children.forEach(child => {
                    const { totalPcs: childPcs, totalWgt: childWgt } = $scope.calculateProductCount(child, filter);
                    totalPcs += childPcs;
                    totalWgt += childWgt;
                });
            }

            return { totalPcs, totalWgt };
        };

        // function buildTree($container, categories, $scope, $compile, level = 0, filter = null) {
        //     categories.forEach(category => {
        //         const { totalPcs, totalWgt } = $scope.calculateProductCount(category, filter);
        //         category.count = totalPcs;
        //         category.totalWeight = totalWgt;
        //         $scope.totalPsc = totalPcs;
        //         $scope.totalWeight = totalWgt;

        //         const { html, bgColor } = renderCategoryRow(category, level);
        //         const borderStyle = `border-left: 2px solid ${shadeColor(bgColor, -40)};`;
        //         const $row = $('<div class="category-row"></div>').html(html);
        //         $container.append($row);
        //         $compile($row.contents())($scope);

        //         // Child categories container
        //         let $childContainer;
        //         if (category.children && category.children.length > 0) {
        //             $childContainer = $(`<div class="child-row pl-3 d-none" style="${borderStyle}"></div>`);
        //             $container.append($childContainer);
        //             buildTree($childContainer, category.children, $scope, $compile, level + 1, filter);
        //         }

        //         // Create container for products
        //         let $productContainer;
        //         const nextColor = levelColors[(level + 1) % levelColors.length];
        //         const gradientBg = `linear-gradient(90deg, ${bgColor}66, ${nextColor}66)`;
        //         const tableBorderStyle = `1px solid ${shadeColor(bgColor, -40)};`;

        //         if (category.products && category.products.length > 0) {
        //             $productContainer = $(`<div class="product-container pl-5 d-none col-lg-6"></div>`);

        //             category.products.forEach(product => {
        //                 const today = new Date().toISOString().split('T')[0];
        //                 const createdDate = new Date(product.created_at).toISOString().split('T')[0];

        //                 const isNew = (createdDate === today) ? '<span class="badge badge-warning ml-2">New</span>' : '';
        //                 $scope.totalPsc = 20;
        //                 if (filter) {
        //                     if (createdDate === today) {
        //                         renderProductList();
        //                     }
        //                 } else {
        //                     renderProductList();
        //                 }

        //                 function renderProductList() {
        //                     const productHtml = `
        //                         <div class="row w-100 px-2 mb-2 rounded product-row" 
        //                             style="border-bottom:${tableBorderStyle}; background:${gradientBg};">
        //                             <div class="col-7 col-md-8 font-weight-bold text-lg">
        //                                 ${product.p_name} - ${product.p_code} - 
        //                                 <b class="text-success text-lg mr-lg-5">${product.qty}</b>
        //                             </div>
        //                             <div class="col-5 col-md-4 text-right">${isNew} ${product.delete}</div>
        //                         </div>`;
        //                     $productContainer.append(productHtml);
        //                 }
        //             });
        //             $container.append($productContainer);
        //         }

        //         // Expand/collapse click
        //         $row.find('.expandable-table-caret').on('click', function (e) {
        //             e.stopPropagation();
        //             if ($childContainer) $childContainer.toggleClass('d-none');
        //             if ($productContainer) $productContainer.toggleClass('d-none');
        //             $(this).find('i').toggleClass('fa-caret-right fa-caret-down');
        //         });
        //     });
        // }

        // --- buildTree (attach data attributes, mark child/product containers) ---
        function buildTree($container, categories, $scope, $compile, level = 0, filter = null) {
            categories.forEach(category => {
                const { totalPcs, totalWgt } = $scope.calculateProductCount(category, filter);
                category.count = totalPcs;
                category.totalWeight = totalWgt;

                const { html, bgColor } = renderCategoryRow(category, level);
                const borderStyle = `border-left: 2px solid ${shadeColor(bgColor, -40)};`;

                // category row
                const $row = $('<div class="category-row"></div>').html(html);
                // store helpful attributes for later expansion
                $row.attr('data-cat-id', category.id);
                $row.attr('data-parent-id', category.p_id || 0);
                $row.data('category', category);
                $container.append($row);
                $compile($row.contents())($scope);

                // children container (immediately after the category row)
                let $childContainer;
                if (category.children && category.children.length > 0) {
                    $childContainer = $(`<div class="child-row pl-3 d-none" style="${borderStyle}"></div>`);
                    $childContainer.attr('data-parent-for', category.id);
                    $container.append($childContainer);
                    buildTree($childContainer, category.children, $scope, $compile, level + 1, filter);
                }

                // product container (immediately after child container if present, otherwise after row)
                let $productContainer;
                const nextColor = levelColors[(level + 1) % levelColors.length];
                const gradientBg = `linear-gradient(90deg, ${bgColor}66, ${nextColor}66)`;
                const tableBorderStyle = `1px solid ${shadeColor(bgColor, -40)};`;

                if (category.products && category.products.length > 0) {
                    $productContainer = $(`<div class="product-container pl-5 d-none col-lg-6" data-products-for="${category.id}"></div>`);

                    category.products.forEach(product => {
                        const today = new Date().toISOString().split('T')[0];
                        const createdDate = new Date(product.created_at).toISOString().split('T')[0];
                        const isNew = (createdDate === today) ? '<span class="badge badge-warning ml-2">New</span>' : '';

                        // Apply filter
                        if (filter === 'new' && createdDate !== today) return; // skip old products

                        const productHtml = `
                            <div class="row w-100 px-2 mb-2 rounded product-row"
                                data-prod-id="${product.id}"
                                data-cat-id="${category.id}"
                                style="border-bottom:${tableBorderStyle}; background:${gradientBg};">
                                <div class="col-7 col-md-8 font-weight-bold text-lg">
                                    ${product.p_name} - ${product.p_code} -
                                    <b class="text-success text-lg mr-lg-5">${product.qty}</b>
                                </div>
                                <div class="col-5 col-md-4 text-right">${isNew} ${product.delete}</div>
                            </div>`;
                        $productContainer.append(productHtml);
                    });

                    $container.append($productContainer);
                }


                // Expand/collapse click (caret)
                $row.find('.expandable-table-caret').on('click', function (e) {
                    e.stopPropagation();
                    if ($childContainer) $childContainer.toggleClass('d-none');
                    if ($productContainer) $productContainer.toggleClass('d-none');
                    $(this).find('i').toggleClass('fa-caret-right fa-caret-down');
                });
            });
        }

        // --- pendingOpenCatId & loadCategoryList signature ---
        // call loadCategoryList(openCatId) to fetch & later open that category
        $scope.pendingOpenCatId = null;
        $scope.loadCategoryList = function (openCatId = null, filter = null, openedCats = [], scroll = true) {
            $scope.pendingOpenCatId = openCatId || null;
            $scope.pendingFilter = filter || null;
            $scope.pendingOpenedCats = openedCats || [];
            $scope.scrollToCat = scroll;

            $http.get("../_inc/_category.php", { params: { action_type: "GET_CAT_DATA" } }).then(function (res) {
                $scope.categoryData = res.data.data;
                $scope.loadCategoryTree($scope.pendingFilter, $scope.pendingOpenedCats, $scope.scrollToCat);
            });
        };


        // --- loadCategoryTree (runs expand after tree is built) ---
        $scope.loadCategoryTree = function (filter = null, openedCats = [], scroll = true) {
            const $treeContainer = $('#category-tree');
            $treeContainer.empty();
            $scope.categories = $scope.categoryData;

            buildTree($treeContainer, $scope.categoryData, $scope, $compile, 0, filter);

            // Expand previously opened categories without scrolling
            if (openedCats.length) {
                setTimeout(() => {
                    openedCats.forEach(catId => expandCategoryPath(catId, false)); // pass scroll = false
                }, 0);
            }

            // Expand pendingOpenCatId only if scroll = true
            if ($scope.pendingOpenCatId && scroll) {
                setTimeout(() => {
                    expandCategoryPath($scope.pendingOpenCatId);
                    $scope.pendingOpenCatId = null;
                }, 0);
            }
        };
        $scope.loadCategoryList();

        // --- expandCategoryPath: opens ancestor path + product list + scrolls into view ---
        function expandCategoryPath(catId, scroll = true) {
            if (!catId) return;

            const $targetRow = $(`.category-row[data-cat-id="${catId}"]`);
            if (!$targetRow.length) return;

            const $prod = $(`.product-container[data-products-for="${catId}"]`);
            if ($prod.length) $prod.removeClass('d-none');

            const $child = $(`.child-row[data-parent-for="${catId}"]`);
            if ($child.length) $child.removeClass('d-none');

            $targetRow.find('.expandable-table-caret i').removeClass('fa-caret-right').addClass('fa-caret-down');

            let parentId = $targetRow.attr('data-parent-id') || 0;
            while (parentId && parentId != '0') {
                const $parentRow = $(`.category-row[data-cat-id="${parentId}"]`);
                if (!$parentRow.length) break;

                const $parentChildContainer = $(`.child-row[data-parent-for="${parentId}"]`);
                if ($parentChildContainer.length) $parentChildContainer.removeClass('d-none');

                $parentRow.find('.expandable-table-caret i').removeClass('fa-caret-right').addClass('fa-caret-down');

                parentId = $parentRow.attr('data-parent-id') || 0;
            }

            if (scroll) {
                try {
                    $('html, body').animate({ scrollTop: $targetRow.offset().top - 80 }, 300);
                } catch (err) { }
            }
        }


        // Track currently opened categories
        $scope.getOpenedCategories = function () {
            const opened = [];
            $('.category-row i.fa-caret-down').each(function () {
                const catId = $(this).closest('.category-row').data('cat-id');
                if (catId) opened.push(catId);
            });
            return opened;
        };

        $scope.toggleFilter = function () {
            $scope.isFilter = !$scope.isFilter;
            const toggleBtn = $("#filterBtn");

            // Save currently opened categories
            const openedCats = $scope.getOpenedCategories();

            const currentFilter = $scope.isFilter ? 'new' : null;
            if ($scope.isFilter) {
                toggleBtn.removeClass("btn-outline-primary").addClass("btn-primary");
            } else {
                toggleBtn.removeClass("btn-primary").addClass("btn-outline-primary");
            }

            // Reload tree with filter & previously opened categories
            $scope.loadCategoryList(null, currentFilter, openedCats, false); // last param: scroll = false
        };


        $scope.$on("PRODUCT_LIST_REFRESH", function () {
            const openedCats = $scope.getOpenedCategories();
            const currentFilter = $scope.isFilter ? 'new' : null;
            $scope.loadCategoryList(null, currentFilter, openedCats, false);
        });


        // $(document).ready(function () {
        //     const $treeContainer = $('#category-tree');
        //     buildTree($treeContainer, categories);
        // });

        $scope.openAddCategoryModal = function (category) {

            let categoryName = '';
            let categoryId = 0;
            if (category) {
                categoryName = category.c_name;
                categoryId = category.id;
            };


            categoryAddModal($scope, { categoryName, id: categoryId });
        };

        // 🔹 Edit/Delete buttons using jQuery delegation

        $(document).delegate(".btn-edit", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();
            console.log('edit')
            var id = $(this).data('id');
            $scope.category = { id: id };
            categoryEditModal($scope);
        });

        // $(document).delegate(".cat-del", "click", function (e) {
        //     e.stopPropagation();
        //     e.preventDefault();
        //     console.log('delete')
        //     var id = $(this).data('id');
        //     $scope.category = {
        //         id: id
        //     };
        //     categoryDeleteModal($scope);
        // });

        $scope.cat_del = function (id) {
            $scope.category = {
                id: id
            };
            categoryDeleteModal($scope);
        }

        $scope.cat_edit = function (id) {
            $scope.category = {
                id: id
            };
            categoryEditModal($scope);
        }
        // --- delete-product handler: read deleted product's c_id, reload and open it ---
        $(document).delegate(".delete-product", "click", function (e) {
            e.stopPropagation();
            e.preventDefault();

            const $btn = $(this);
            const $productRow = $btn.closest('.product-row');
            const productId = $productRow.data('prod-id') || $productRow.attr('data-prod-id');
            const productCatId = $productRow.data('cat-id') || $productRow.attr('data-cat-id');

            $scope.Product = { id: productId, status: 1 };

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('action_type', 'DELETE');
                    formData.append('id', $scope.Product.id);

                    $http({
                        url: window.baseUrl + "/_inc/_product.php",
                        method: "POST",
                        data: formData,
                        transformRequest: angular.identity,
                        headers: { 'Content-Type': undefined }
                    }).then(
                        function (response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.data.msg,
                                icon: "success"
                            });

                            // Reload the category tree and open the deleted product's category
                            // Respect current filter
                            const currentFilter = $scope.isFilter ? 'new' : null;
                            $scope.loadCategoryList(productCatId, currentFilter);

                        },
                        function (response) {
                            var alertMsg = "";
                            angular.forEach(response.data, function (value) {
                                alertMsg += value + " ";
                            });
                            Swal.fire({
                                title: "Oops!",
                                text: alertMsg || "Delete failed!",
                                icon: "error"
                            });
                        }
                    );
                }
            });
        });




    }
]);