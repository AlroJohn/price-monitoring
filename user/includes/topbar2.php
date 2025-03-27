<!-- Additional Topbar with Search -->
<div class="bg-blue-600 shadow-md py-3 px-6 flex justify-between items-center">
    <!-- Logo -->
    <div>
        <img src="../../img/logo.png" alt="Logo" class="h-20">
    </div>

    <!-- Search Bar -->
    <!-- <form action="search_handler.php" method="GET" class="w-1/2 relative">
        <input type="text" id="searchInput" name="query" placeholder="Search..."
            class="border rounded-lg px-4 py-2 w-full focus:ring focus:ring-blue-300" onkeyup="liveSearch(this.value)">
        <div id="searchResults" class="absolute top-full left-0 w-full bg-white shadow-lg rounded-lg mt-1 z-50 hidden">
        </div>
    </form> -->

    <!-- Reserved Space -->
    <div></div>

    <script>
        function liveSearch(query) {
            let productSection = document.getElementById("productSection");
            let storeCategories = document.getElementById("storeCategories");

            if (query.trim().length === 0) {
                // Show the store categories again when the search is empty
                storeCategories.style.display = "block";
                window.location.href = "index.php?category=All"; // Reload stores
                return;
            } else {
                // Hide the store categories section when searching
                storeCategories.style.display = "none";
            }

            fetch('search_handler.php?query=' + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    productSection.innerHTML = data;
                });
        }
    </script>


</div>