document.addEventListener("DOMContentLoaded", function() {
    console.log("Danh sách JS đã chạy!");
    
    const buttons = document.querySelectorAll(".view-toggle button");

    buttons.forEach(button => {
        button.addEventListener("click", function() {
            buttons.forEach(btn => {
                btn.classList.remove("active");
                btn.querySelector(".text").style.display = "none"; 
            });

            this.classList.add("active");
            this.querySelector(".text").style.display = "inline";
        });
    });
});
