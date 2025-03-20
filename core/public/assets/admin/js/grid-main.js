
console.log('123123');

document.addEventListener("DOMContentLoaded", function() {
    console.log("Lưới JS đã chạy!");
    
    // Logic JS cho giao diện dạng Lưới
    document.querySelectorAll(".grid-item").forEach(item => {
        item.addEventListener("click", function() {
            alert("Bạn đã chọn một mục trong Grid!");
        });
    });
});

function toggleFloor(id) {
    var floor = document.getElementById(id);
    var icon = floor.previousElementSibling.querySelector("i");
    if (floor.style.display === "none") {
        floor.style.display = "grid";
        icon.classList.remove("fa-chevron-right");
        icon.classList.add("fa-chevron-down");
    } else {
        floor.style.display = "none";
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-right");
    }
}