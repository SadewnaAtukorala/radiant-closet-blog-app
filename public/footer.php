</main>

<footer>

    <p>
        © 2026 The Radiant Closet. All rights reserved.
    </p>

</footer>

<!-- DELETE CONFIRMATION MODAL -->

<div class="delete-modal" id="delete-modal">

    <div class="delete-modal-content">

        <h2>Delete this blog?</h2>

        <p>
            Are you sure you want to delete this blog?
            This action cannot be undone.
        </p>

        <div class="delete-modal-actions">

            <button
                type="button"
                class="cancel-delete"
                id="cancel-delete"
            >
                Cancel
            </button>

            <button
                type="button"
                class="confirm-delete"
                id="confirm-delete"
            >
                Delete Blog
            </button>

        </div>

    </div>

</div>

<script>

    const deleteModal = document.getElementById("delete-modal");
    const cancelDelete = document.getElementById("cancel-delete");
    const confirmDelete = document.getElementById("confirm-delete");

    let selectedDeleteForm = null;


    // Open delete confirmation

    document.querySelectorAll(".delete-trigger").forEach(function(button) {

        button.addEventListener("click", function() {

            selectedDeleteForm = button.closest(".delete-form");

            deleteModal.classList.add("active");

        });

    });


    // Cancel deletion

    cancelDelete.addEventListener("click", function() {

        deleteModal.classList.remove("active");

        selectedDeleteForm = null;

    });


    // Confirm deletion

    confirmDelete.addEventListener("click", function() {

        if (selectedDeleteForm) {

            selectedDeleteForm.submit();

        }

    });


    // Close modal when clicking outside

    deleteModal.addEventListener("click", function(event) {

        if (event.target === deleteModal) {

            deleteModal.classList.remove("active");

            selectedDeleteForm = null;

        }

    });

</script>

</body>

</html>