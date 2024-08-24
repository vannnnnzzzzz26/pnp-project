
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content');
        sidebar.classList.toggle('collapsed');
        content.classList.toggle('expanded');
    }
   


 
    // JavaScript to handle modal data
    var editOfficialModal = document.getElementById('editOfficialModal');
    editOfficialModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;

        // Extract data from button
        var official_id = button.getAttribute('data-official-id');
        var name = button.getAttribute('data-name');
        var position = button.getAttribute('data-position');

        // Populate the modal with data
        var modalTitle = editOfficialModal.querySelector('.modal-title');
        modalTitle.innerHTML = 'Edit Official';

        var editOfficialIdInput = editOfficialModal.querySelector('#edit_official_id');
        editOfficialIdInput.value = official_id;

        var editNameInput = editOfficialModal.querySelector('#edit_name');
        editNameInput.value = name;

        var editPositionInput = editOfficialModal.querySelector('#edit_position');
        editPositionInput.value = position;
    });




    


    