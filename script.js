// for venue capcity
$("#venue").on("change", function () {
    const venue = $(this).val(); // Get the selected venue
    const $capacityInput = $("#capacity"); // Get the capacity input field

    // Set max capacity based on venue
    let maxCapacity;
    switch (venue) {
        case "Auditorium":
            maxCapacity = 200;
            break;
        case "Gymnasium":
            maxCapacity = 1000;
            break;
        case "Museum":
            maxCapacity = 150;
            break;
        case "Theatre":
            maxCapacity = 125;
            break;
        default:
            maxCapacity = "";
    }

    $capacityInput.attr("max", maxCapacity); // Update the max attribute

    // Reset the value if it exceeds the new max
    if ($capacityInput.val() > maxCapacity) {
        $capacityInput.val(maxCapacity);
    }
});


// $(document).ready(function () {
//     // Toggle the sidebar when the menu icon is clicked
//     $("#menu-icon").on("click", function (e) {
//         e.preventDefault(); // Prevent default link behavior
//         $("#small-sidebar").toggleClass("active"); // Toggle the "active" class on the sidebar
//     });

//     // Close the sidebar when the close button is clicked
//     $("#close-sidebar").on("click", function (e) {
//         e.preventDefault(); // Prevent default link behavior
//         $("#small-sidebar").removeClass("active"); // Remove the "active" class to hide the sidebar
//     });
// });

function openSidebar() {
    document.getElementById("sidebar").style.left = "0";
}

function closeSidebar() {
    document.getElementById("sidebar").style.left = "-250px";
}