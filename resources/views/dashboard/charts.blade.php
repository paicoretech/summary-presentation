@extends('dashboard.base')

@section('content')

        

        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol id="breadcrumb" class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">Root</li>
                </ol>
            </nav>
        
            <!-- Search input -->
            <input type="text" id="searchInput" placeholder="Search folders..." class="form-control mb-3">
        
            <!-- Folder and dashboard list -->
            <div id="folderContainer" class="list-group">
                <!-- Populated dynamically -->
            </div>
        
            <!-- Iframe for dashboards -->
            <div class="mt-4">
                <iframe id="dashboardIframe" src="" width="100%" height="600" frameborder="0" style="display: none;"></iframe>
            </div>
        </div>
        
        
        

@endsection

@section('javascript')
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/integratedSequenceDiagram.js') }}"></script>
    <script src="{{ asset('js/jstree.min.js') }}"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const treeData = @json($treeData); // Backend-provided hierarchical data
            console.log(treeData);

            const formattedData = {};
            treeData.forEach(item => {
                const parentId = item.parent;

                if (!formattedData[item.id]) {
                    formattedData[item.id] = { ...item, children: [] };
                }

                if (parentId && parentId !== "#") {
                    if (!formattedData[parentId]) {
                        formattedData[parentId] = { id: parentId, children: [] };
                    }
                    formattedData[parentId].children.push(formattedData[item.id]);
                }
            });

            const rootItems = Object.values(formattedData).filter(item => item.parent === "#");

            const breadcrumb = document.getElementById("breadcrumb");
            const folderContainer = document.getElementById("folderContainer");
            const dashboardIframe = document.getElementById("dashboardIframe");
            const searchInput = document.getElementById("searchInput");

            let navigationStack = [{ id: "root", name: "Root", children: rootItems }];

            function renderList(items) {
                folderContainer.innerHTML = "";
                items.forEach((item) => {
                    const listItem = document.createElement("button");
                    listItem.className = "list-group-item list-group-item-action d-flex align-items-center";
                    listItem.dataset.id = item.id;

                    const icon = document.createElement("i");
                    icon.className = item.type === "folder" ? "fa fa-folder text-warning me-2 pr-1" : "fa fa-dashboard text-info me-2 pr-1";
                    listItem.appendChild(icon);

                    const textNode = document.createTextNode(item.text);
                    listItem.appendChild(textNode);

                    if (item.type === "folder") {
                        listItem.addEventListener("click", () => navigateTo(item));
                    } else if (item.type === "dashboard") {
                        listItem.addEventListener("click", () => openDashboard(item));
                    }

                    folderContainer.appendChild(listItem);
                });
            }

            function updateBreadcrumb() {
                breadcrumb.innerHTML = "";

                navigationStack.forEach((item, index) => {
                    const isLast = index === navigationStack.length - 1;
                    const li = document.createElement("li");
                    li.className = `breadcrumb-item ${isLast ? "active" : ""}`;
                    li.textContent = item.name;

                    // Make the last breadcrumb clickable if viewing a dashboard
                    if (!isLast || (isLast && dashboardIframe.style.display === "block")) {
                        li.style.cursor = "pointer";
                        li.addEventListener("click", () => navigateToBreadcrumb(index));
                    }

                    breadcrumb.appendChild(li);
                });

                if (navigationStack.length === 1) {
                    searchInput.style.display = "block";
                } else {
                    searchInput.style.display = "none";
                }
            }

            function navigateTo(folder) {
                navigationStack.push({ id: folder.id, name: folder.text, children: folder.children });
                renderList(folder.children);
                updateBreadcrumb();
                dashboardIframe.style.display = "none";
                folderContainer.style.display = "block";
            }

            function navigateToBreadcrumb(index) {
                navigationStack = navigationStack.slice(0, index + 1);
                const current = navigationStack[navigationStack.length - 1];
                renderList(current.children);
                updateBreadcrumb();
                dashboardIframe.style.display = "none";
                folderContainer.style.display = "block";
            }

            function openDashboard(dashboard) {
                dashboardIframe.src = dashboard.data.url;
                dashboardIframe.style.display = "block";
                folderContainer.style.display = "none";
                updateBreadcrumb();
            }

            function returnToList() {
                const current = navigationStack[navigationStack.length - 1];
                renderList(current.children);
                dashboardIframe.style.display = "none";
                folderContainer.style.display = "block";
                updateBreadcrumb();
            }

            searchInput.addEventListener("input", function () {
                const query = searchInput.value.toLowerCase();
                const current = navigationStack[navigationStack.length - 1].children;
                const filtered = current.filter((item) => item.text.toLowerCase().includes(query));
                renderList(filtered);
            });

            renderList(rootItems);
            updateBreadcrumb();
        });
    
    </script>

@endsection