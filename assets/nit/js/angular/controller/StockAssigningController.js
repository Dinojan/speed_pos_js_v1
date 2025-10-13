angularApp.controller("StockAssigningController", [
    "$scope",
    "API_URL",
    "window",
    "jQuery",
    "$compile",
    "$uibModal",
    "$http",
    "$sce",
    function ($scope, API_URL, window, $, $compile, $uibModal, $http, $sce) {
        // High-Contrast Palette  
        const levelColors = ["#4285F4", "#34A853", "#FF6B9D", "#FB8C00", "#AB47BC", "#3949AB", "#E53935", "#FFD700", "#BDBDBD", "#26C6DA"];

        $scope.isFilter = false;
        $scope.categoryData = [];
        $scope.selectedSubcategoryId = null;
        $scope.currentMainCategory = null;
        $scope.categoryPath = [];

        function shadeColor(color, percent) {
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

        function renderMainCategoryRow(data, level) {
            const bgColor = levelColors[level % levelColors.length];
            const nextColor = levelColors[(level + 1) % levelColors.length];
            const hoverColor = shadeColor(bgColor, -20);
            const gradientBg = `linear-gradient(90deg, ${bgColor}aa, ${nextColor}aa)`;

            const html = `
                <div class="row col-lg-6 align-items-center hover-wrapper px-2 my-1 rounded category-clickable text-lg"
                    style="background:${gradientBg}; cursor:pointer;"
                    onmouseenter="this.style.background='linear-gradient(90deg, ${shadeColor(bgColor, -20)}cc, ${shadeColor(nextColor, -20)}cc)'"
                    onmouseleave="this.style.background='${gradientBg}'"
                    data-category-id="${data.id}">
                    <div class="col-lg-4">
                        <div class="py-2">
                            <b>${data.sl}. </b>
                            ${data.c_name}
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="py-2">
                            (<span class="cat-count">${data.count}</span> pcs)
                            <span class="cat-weight">${(data.totalWeight || 0).toFixed(2)}</span>g
                        </div>
                    </div>
                </div>
            `;
            return { html, bgColor };
        }

        $scope.calculateProductCount = function (category) {
            let totalPcs = 0;
            let totalWgt = 0;

            // Count current category products
            if (category.products) {
                category.products.forEach(product => {
                    if (product.status === 0) {
                        totalPcs += parseFloat(product.qty) || 0;
                        totalWgt += (parseFloat(product.wgt) || 0) * (parseFloat(product.qty) || 0);
                    }
                });
            }

            // Recursively count child categories
            if (category.children && category.children.length > 0) {
                category.children.forEach(child => {
                    const { totalPcs: childPcs, totalWgt: childWgt } = $scope.calculateProductCount(child);
                    totalPcs += childPcs;
                    totalWgt += childWgt;
                });
            }

            return { totalPcs, totalWgt };
        };

        // Get ALL subcategories recursively
        function getAllSubcategories(category) {
            let subcategories = [];

            if (category.children && category.children.length > 0) {
                category.children.forEach(child => {
                    subcategories.push(child);
                    subcategories = subcategories.concat(getAllSubcategories(child));
                });
            }

            return subcategories;
        }

        // Get ALL products from category and ALL nested subcategories recursively
        function getAllCategoryProducts(category) {
            let products = [];

            // Add current category products
            if (category.products) {
                products = products.concat(category.products.filter(product => product.status === 0));
            }

            // Add ALL nested subcategory products recursively
            if (category.children && category.children.length > 0) {
                category.children.forEach(child => {
                    products = products.concat(getAllCategoryProducts(child));
                });
            }

            return products;
        }

        // Calculate total count and weight for a category and ALL its subcategories
        function calculateCategoryTotals(category) {
            const products = getAllCategoryProducts(category);
            let totalPcs = 0;
            let totalWgt = 0;

            products.forEach(product => {
                totalPcs += parseFloat(product.qty) || 0;
                totalWgt += (parseFloat(product.wgt) || 0) * (parseFloat(product.qty) || 0);
            });

            return { totalPcs, totalWgt };
        }

        // Get direct subcategories only (for display in the popover)
        function getDirectSubcategories(category) {
            return category.children && category.children.length > 0 ? category.children : [];
        }

        // Build category path - FIXED VERSION
        function buildCategoryPath(mainCategory, selectedCategoryId) {
            const path = [];

            // Always include main category as first item
            path.push({
                id: mainCategory.id,
                name: mainCategory.c_name,
                isMain: true
            });

            // If a subcategory is selected, find its path
            if (selectedCategoryId && selectedCategoryId !== mainCategory.id) {
                function findPath(category, targetId, currentPath) {
                    // Add current category to path
                    const newPath = [...currentPath, {
                        id: category.id,
                        name: category.c_name,
                        isMain: false
                    }];

                    // If this is the target category, return the path
                    if (category.id == targetId) {
                        return newPath;
                    }

                    // Search in children
                    if (category.children && category.children.length > 0) {
                        for (let child of category.children) {
                            const result = findPath(child, targetId, newPath);
                            if (result) {
                                return result;
                            }
                        }
                    }

                    return null;
                }

                const foundPath = findPath(mainCategory, selectedCategoryId, []);
                if (foundPath) {
                    // Remove the duplicate main category (it's already in path[0])
                    foundPath.shift(); // Remove the main category duplicate
                    path.push(...foundPath);
                }
            }

            return path;
        }

        // Find category by ID in the hierarchy
        function findCategoryById(category, categoryId) {
            if (category.id == categoryId) {
                return category;
            }

            if (category.children && category.children.length > 0) {
                for (let child of category.children) {
                    const found = findCategoryById(child, categoryId);
                    if (found) return found;
                }
            }

            return null;
        }

        // Update popover content when category is selected
        function updatePopoverContent(mainCategory, selectedCategoryId) {
            const selectedCategory = selectedCategoryId ?
                findCategoryById(mainCategory, selectedCategoryId) : mainCategory;

            if (!selectedCategory) return;

            const directSubcategories = getDirectSubcategories(selectedCategory);
            const products = getAllCategoryProducts(selectedCategory);
            const categoryTotals = calculateCategoryTotals(selectedCategory);

            // Build category path
            $scope.categoryPath = buildCategoryPath(mainCategory, selectedCategoryId);

            // Mobile detection
            const isMobile = window.innerWidth < 768;
            const isTablet = window.innerWidth >= 768 && window.innerWidth < 992;

            const popoverContent = `
                <div class="category-popover p-2 p-md-3 position-relative ${isMobile ? 'mobile-view' : ''}" 
                    style="min-width: ${isMobile ? '280px' : '350px'}; max-width: ${isMobile ? '95vw' : ''};">
                    
                    <!-- Close Button - Mobile Optimized -->
                    <button class="btn btn-lg text-danger position-absolute" 
                            style="top:5px; right:5px; z-index:10;"
                            onclick="angular.element(this).scope().closePopover()">
                        <i class="fas fa-times"></i>
                    </button>

                    <!-- Category Path - Mobile Optimized -->
                    <div class="category-path mb-3 p-2 bg-light rounded mt-2">
                        <h6 class="mb-2 text-muted">
                            <i class="fas fa-map-marker-alt mr-1"></i>Current Location:
                        </h6>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0" style="font-size: 14px;"> <!-- Fixed font size -->
                                ${$scope.categoryPath.map((cat, index) => `
                                    <li class="breadcrumb-item ${index === $scope.categoryPath.length - 1 ? 'active font-weight-bold text-primary' : ''}" style="font-size: 14px;">
                            
                                        <a href="javascript:void(0)" 
                                        onclick="angular.element(this).scope().selectSubcategory(${cat.id})"
                                        class="${index === $scope.categoryPath.length - 1 ? 'text-primary' : 'text-dark'}" 
                                        style="font-size: 14px; text-decoration: none;">
                                            ${cat.name}
                                        </a>
                                    </li>
                                `).join('')}
                            </ol>
                        </nav>
                    </div>

                    <!-- Category Totals - Mobile Stacked -->
                    <div class="category-totals mb-3 p-2 p-md-3 bg-primary text-white rounded">
                        <div class="row text-center ${isMobile ? '' : 'row-cols-2'}">
                            <div class="col ${isMobile ? 'mb-2' : ''}">
                                <h4 class="mb-1 ${isMobile ? 'h5' : ''}">${categoryTotals.totalPcs}</h4>
                                <small>Total Pieces</small>
                            </div>
                            <div class="col">
                                <h4 class="mb-1 ${isMobile ? 'h5' : ''}">${categoryTotals.totalWgt.toFixed(2)}g</h4>
                                <small>Total Weight</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Main Content - Mobile Stacked -->
                    <div class="row ${isMobile ? 'g-2' : ''}" style="max-height: ${isMobile ? '600px' : '620px'}; overflow-y: auto;">
                        
                        <!-- Subcategories Section -->
                        <div class="${isMobile ? 'col-12 mb-3' : 'col-md-6'}">
                            <div class="d-flex flex-row justify-content-between align-item-center mb-2">
                                <h6 class="text-primary ${isMobile ? 'small' : ''}">
                                    <i class="fas fa-sitemap mr-1"></i>
                                    Subcategories (${directSubcategories.length})
                                </h6>
                            </div>
                            <div class="subcategories-list" style="max-height: ${isMobile ? '250px' : '580px'}; overflow-y: auto;">
                                ${directSubcategories.length > 0 ? directSubcategories.map(subcat => {
                const subcatTotals = calculateCategoryTotals(subcat);
                return `
                                            <div class="subcategory-item p-2 mb-2 rounded border ${selectedCategoryId === subcat.id ? 'border-primary bg-light' : ''}" 
                                                style="background: ${selectedCategoryId === subcat.id ? '#e3f2fd' : '#f8f9fa'}; cursor: pointer;"
                                                onclick="angular.element(this).scope().selectSubcategory(${subcat.id})">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="font-weight-bold ${isMobile ? 'small' : ''}">
                                                                ${isMobile && subcat.c_name.length > 20 ? subcat.c_name.substring(0, 20) + '...' : subcat.c_name}
                                                    </div>
                                                    ${hasChildren(subcat) ? '<small class="text-info ml-2"><i class="fas fa-chevron-right"></i></small>' : ''}
                                                </div>
                                                <div class="mt-1 ${isMobile ? 'small' : ''}">
                                                    <small class="text-muted">
                                                        <i class="fas fa-cube mr-1"></i>${subcatTotals.totalPcs} pcs
                                                    </small>
                                                    <small class="text-muted ml-2">
                                                        <i class="fas fa-weight-hanging mr-1"></i>${subcatTotals.totalWgt.toFixed(2)}g
                                                    </small>
                                                </div>
                                            </div>
                                    `}).join('') :
                    '<div class="text-muted p-2 text-center small">No subcategories</div>'
                }
                            </div>
                        </div>
                        
                        <!-- Products Section -->
                        <div class="${isMobile ? 'col-12' : 'col-md-6'} ">
                            <div class="d-flex flex-row justify-content-between align-item-center mb-2">
                                <h6 class="text-success ${isMobile ? 'small' : ''}">
                                    <i class="fas fa-cubes mr-1"></i>
                                    Jewelries (${products.length})
                                </h6>
                                <button id="assignToCheck" class="btn btn-primary btn-lg py-1"><i class="fas fa-arrow-alt-circle-right mr-2"></i>Assign</button> 
                            </div>
                            <div class="products-list" style="max-height: ${isMobile ? '280px' : '580px'}; overflow-y: auto;">
                                ${products.length > 0 ? products.map(product => `
                                        <div class="product-item p-2 mb-2 rounded border" 
                                            style="background: #f0f8ff;">
                                            <div class="font-weight-bold text-truncate ${isMobile ? 'small' : ''}">
                                                ${isMobile && product.p_name.length > 25 ? product.p_name.substring(0, 25) + '...' : product.p_name}
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1 ${isMobile ? 'small' : ''}">
                                                <small class="text-muted">${product.p_code}</small>
                                                <div>
                                                    <span class="badge badge-success mr-1">${product.qty} pcs</span>
                                                    ${product.wgt ? `<span class="badge badge-info">${product.wgt}g</span>` : ''}
                                                </div>
                                            </div>
                                            <div class="mt-1 ${isMobile ? 'small' : ''}">
                                                <small class="text-muted">
                                                    Total: ${(parseFloat(product.wgt || 0) * parseFloat(product.qty || 0)).toFixed(2)}g
                                                </small>
                                            </div>
                                        </div>
                                    `).join('') :
                    '<div class="text-muted p-2 text-center small">No products</div>'
                }
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons - Mobile Optimized -->
                    <!-- <div class="mt-3 pt-2 border-top ${isMobile ? 'text-center' : 'text-right'}">
                        ${selectedCategoryId ? `
                            <button class="btn btn-lg btn-secondary ${isMobile ? 'btn-block' : 'mr-2'} mb-1" 
                                    onclick="angular.element(this).scope().goBack()">
                                <i class="fas fa-arrow-left mr-1"></i> Back
                            </button>
                        ` : ''}
                    </div>
                    -->
                </div>
            `;

            $('#categoryPopover .modal-body').html(popoverContent);
            $compile($('#categoryPopover .modal-body'))($scope);
        }
        // Helper function to check if category has children
        function hasChildren(category) {
            return category.children && category.children.length > 0;
        }

        // Navigate back in category path
        $scope.goBack = function () {
            if ($scope.categoryPath.length > 1) {
                const previousCategoryId = $scope.categoryPath[$scope.categoryPath.length - 2].id;
                $scope.selectSubcategory(previousCategoryId);
            }
        };

        // Select subcategory - shows only its direct subcategories
        $scope.selectSubcategory = function (categoryId) {
            $scope.selectedSubcategoryId = categoryId;
            if ($scope.currentMainCategory) {
                updatePopoverContent($scope.currentMainCategory, categoryId);
            }
        };

        // Auto-close popover when clicking outside
        function setupAutoClose() {
            $('#categoryPopoverBackdrop').on('click', function () {
                $scope.closePopover();
            });

            // Also close when pressing Escape key
            $(document).on('keyup.popoverClose', function (event) {
                if (event.key === 'Escape') {
                    $scope.closePopover();
                }
            });
        }

        // Open popover with main category
        function openCategoryPopover(category) {
            $scope.currentMainCategory = category;
            $scope.selectedSubcategoryId = null;
            $scope.categoryPath = [];

            // Create and show popover
            $('body').append(`
                <div class="modal-backdrop fade show" id="categoryPopoverBackdrop"></div>
            `);

            const $popover = $(`
                <div class="modal fade show" id="categoryPopover" tabindex="-1" style="display: block;">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body p-0">
                                <!-- Content will be populated by updatePopoverContent -->
                            </div>
                        </div>
                    </div>
                </div>
            `).appendTo('body');

            $compile($popover)($scope);

            // Setup auto-close functionality
            setupAutoClose();

            // Initial content update
            updatePopoverContent(category, null);
        }

        // Close popover
        $scope.closePopover = function () {
            // Remove event listeners
            $(document).off('click.popoverClose');
            $(document).off('keyup.popoverClose');

            // Remove elements
            $('#categoryPopover').remove();
            $('#categoryPopoverBackdrop').remove();
            $scope.currentMainCategory = null;
            $scope.selectedSubcategoryId = null;
            $scope.categoryPath = [];
        };

        // Build main categories only
        function buildMainCategories($container, categories, $scope, $compile) {
            const mainCategories = categories.filter(cat => !cat.p_id || cat.p_id == 0);

            mainCategories.forEach((category, index) => {
                const { totalPcs, totalWgt } = $scope.calculateProductCount(category);
                category.count = totalPcs;
                category.totalWeight = totalWgt;

                const { html, bgColor } = renderMainCategoryRow(category, index);

                const $row = $('<div class="main-category-row"></div>').html(html);
                $row.attr('data-cat-id', category.id);
                $row.data('category', category);
                $container.append($row);
                $compile($row.contents())($scope);

                // Add click event to open popover
                $row.find('.category-clickable').on('click', function (e) {
                    e.stopPropagation();
                    openCategoryPopover(category);
                });
            });
        }

        // Load category list
        $scope.loadCategoryList = function () {
            $http.get("../_inc/_category.php", { params: { action_type: "GET_CAT_DATA" } }).then(function (res) {
                $scope.categoryData = res.data.data;
                $scope.loadCategoryTree();
            });
        };

        // Load category tree (main categories only)
        $scope.loadCategoryTree = function () {
            const $treeContainer = $('#category-tree');
            $treeContainer.empty();
            $scope.categories = $scope.categoryData;

            buildMainCategories($treeContainer, $scope.categoryData, $scope, $compile);
        };

        // Initialize
        $scope.loadCategoryList();

        // Refresh event
        $scope.$on("PRODUCT_LIST_REFRESH", function () {
            $scope.loadCategoryList();
        });

        // Handle assign button click
        $scope.handleAssignToCheck = function () {
            const selectedCategory = $scope.selectedSubcategoryId ?
                findCategoryById($scope.currentMainCategory, $scope.selectedSubcategoryId) :
                $scope.currentMainCategory;

            if (!selectedCategory) return;

            const products = getAllCategoryProducts(selectedCategory);

            if (products.length === 0) {
                Toast.fire('No products available to assign!');
                return;
            }

            // Prepare data for transfer
            // const transferData = {
            // category_id: selectedCategory.id,
            // category_name: selectedCategory.c_name,
            // products: products,
            // total_pieces: products.reduce((sum, product) => sum + (parseFloat(product.qty) || 0), 0),
            // total_weight: products.reduce((sum, product) => sum + ((parseFloat(product.wgt) || 0) * (parseFloat(product.qty) || 0)), 0)
            // };

            let formData = new FormData();
            formData.append('products', JSON.stringify(products));

            // Send data to server
            $http({
                url: window.baseUrl + "/_inc/_stock_checking.php?action_type=ASSIGN",
                method: "POST",
                data: formData,
                headers: { 'Content-Type': undefined },
                transformRequest: angular.identity,
                responseType: "json"
            }).then(function (response) {
                if (response.data.msg) {
                    Toast.fire({ icon: 'success', title: 'Success!', text: 'Products successfully assigned for stock checking!' });
                    $scope.closePopover();
                    $scope.$emit("PRODUCT_LIST_REFRESH");
                    window.location.href = window.baseUrl + '/_admin/stock_checking.php'
                } else {
                    Toast.fire({ icon: 'error', title: 'Opps!', text: response.data.message || 'Failed to assign products' });
                }
            })
                .catch(function (error) {
                    console.error('Assignment error:', error);
                    Toast.fire({ icon: 'error', title: 'Opps!', text: 'Error assigning products for stock checking' });
                });
        };

        $(document).off("click", "#assignToCheck").on("click", "#assignToCheck", function (e) {
            e.preventDefault();
            $scope.$apply(() => $scope.handleAssignToCheck());
        })
    }
]);