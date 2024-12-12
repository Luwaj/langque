document.addEventListener("DOMContentLoaded", function() {
    const expandableItems = document.querySelectorAll(".expandable");

    expandableItems.forEach(item => {
        item.addEventListener("click", function() {
            this.classList.toggle("active");
        });
    });
});
