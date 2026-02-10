const menuForm = document.getElementById('menuForm');
let currentAction = 'create'; // default action
let currentMenuId = null;

function openModal(mode, menuId = null) {
    const modal = document.getElementById('menuModal');
    const title = document.getElementById('modalTitle');

    if (mode === 'add') {
        title.textContent = 'Add New Menu';
        currentAction = 'create';
        currentMenuId = null;
        menuForm.reset();
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
                document.getElementById('description').value = menu.description;
                document.getElementById('status').value = menu.status;
            })
            .catch(err => console.error(err));
    }

    modal.classList.add('active');
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
    document.getElementById('menuModal').classList.remove('active');
}

function deleteMenu(menuId) {
    if (!confirm('Are you sure you want to delete this menu item?')) return;

    fetch(`../controllers/menusController.php?action=delete`, {
        method: 'POST',
        body: new URLSearchParams({ menu_id: menuId })
    })
    .then(res => res.text())
    .then(data => location.reload())
    .catch(err => console.error(err));
}
