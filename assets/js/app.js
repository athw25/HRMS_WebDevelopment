// ==============================
// Position Module
// ==============================

document.addEventListener("DOMContentLoaded", function () {

    const deleteButtons = document.querySelectorAll(".btn-delete-position");
    const modalElement = document.getElementById("deleteConfirmModal");

    if (deleteButtons.length > 0 && modalElement) {

        const modal = new bootstrap.Modal(modalElement);

        const positionName =
            document.getElementById("deletePositionName");

        const confirmButton =
            document.getElementById("btnConfirmDeleteUrl");

        deleteButtons.forEach(function (button) {

            button.addEventListener("click", function () {

                const id = this.dataset.id;
                const name = this.dataset.name;

                positionName.textContent = name;

                confirmButton.href =
                    "position-delete.php?id=" + id;

                modal.show();

            });

        });

    }

});