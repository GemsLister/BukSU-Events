$(document).ready(function () {
    // Toggle the sidebar when the menu icon is clicked
    $("#menu-icon").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior
        $("#small-sidebar").toggleClass("active"); // Toggle the "active" class on the sidebar
    });

    // Close the sidebar when the close button is clicked
    $("#close-sidebar").on("click", function (e) {
        e.preventDefault(); // Prevent default link behavior
        $("#small-sidebar").removeClass("active"); // Remove the "active" class to hide the sidebar
    });
});


