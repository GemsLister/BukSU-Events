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

function openSidebar() {
    document.getElementById("sidebar").style.left = "0";
}

function closeSidebar() {
    document.getElementById("sidebar").style.left = "-250px";
}