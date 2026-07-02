/**
 * Position Module
 * Xử lý Modal xác nhận xóa chức vụ
 */

document.addEventListener("DOMContentLoaded", function () {

    const deletePositionButtons =
        document.querySelectorAll(".btn-delete-position");

    const deletePositionModalElement =
        document.getElementById("deleteConfirmModal");

    if (
        deletePositionButtons.length === 0 ||
        !deletePositionModalElement
    ) {
        return;
    }

    const deletePositionModal =
        new bootstrap.Modal(deletePositionModalElement);

    const deletePositionName =
        document.getElementById("deletePositionName");

    const btnConfirmDeleteUrl =
        document.getElementById("btnConfirmDeleteUrl");

    deletePositionButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const id = this.dataset.id;
            const name = this.dataset.name;

            deletePositionName.textContent = name;

            btnConfirmDeleteUrl.href =
                "position-delete.php?id=" + id;

            deletePositionModal.show();

        });

    });

});