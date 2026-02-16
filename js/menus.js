const menuForm = document.getElementById('menuForm');
let currentAction = 'create'; // default action
let currentMenuId = null;

function openModal(mode, menuId = null) {
    const modal = document.getElementById('menuModal');
    const title = document.getElementById('modalTitle');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');

    if (mode === 'add') {
        title.textContent = 'Add New Menu';
        currentAction = 'create';
        currentMenuId = null;
        menuForm.reset();
        imagePreviewContainer.style.display = 'none';
        imagePreview.src = '';
    } else {
        title.textContent = 'Edit Menu';
        currentAction = 'edit';
        currentMenuId = menuId;

        // Fetch existing menu data
        fetch(`../controllers/menusController.php?action=get&menu_id=${menuId}`)
            .then(res => res.json())
            .then(menu => {
                document.getElementById('menuId').value = menu.menu_id;
                document.getElementById('menuName').value = menu.menu_name;
                document.getElementById('category').value = menu.category;
                document.getElementById('price').value = menu.price;
                document.getElementById('description').value = menu.description || '';
                document.getElementById('status').value = menu.status;
                document.getElementById('currentImage').value = menu.product_image || '';
                
                // Show existing image if available
                if (menu.product_image) {
                    imagePreview.src = `../uploads/${menu.product_image}`;
                    imagePreviewContainer.style.display = 'block';
                } else {
                    imagePreviewContainer.style.display = 'none';
                }
            })
            .catch(err => console.error(err));
    }

    modal.classList.add('active');
}

// Preview image before upload
function previewImage(event) {
    const file = event.target.files[0];
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreviewContainer.style.display = 'block';
        }
        
        reader.readAsDataURL(file);
    } else {
        imagePreviewContainer.style.display = 'none';
        imagePreview.src = '';
    }
}

menuForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Append action for the controller
    const actionParam = currentAction === 'edit' ? 'edit' : 'create';

    fetch(`../controllers/menusController.php?action=${actionParam}`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        closeModal();
        location.reload(); // refresh table
    })
    .catch(err => console.error(err));
});

function closeModal() {
    const modal = document.getElementById('menuModal');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    
    modal.classList.remove('active');
    imagePreviewContainer.style.display = 'none';
    imagePreview.src = '';
}

function deleteMenu(menuId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`../controllers/menusController.php?action=delete`, {
                method: 'POST',
                body: new URLSearchParams({ menu_id: menuId })
            })
            .then(res => res.text())
            .then(data => {
                Swal.fire(
                    'Deleted!',
                    'Menu item has been deleted.',
                    'success'
                ).then(() => {
                    location.reload();
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire(
                    'Error!',
                    'Failed to delete menu item.',
                    'error'
                );
            });
        }
    });
}