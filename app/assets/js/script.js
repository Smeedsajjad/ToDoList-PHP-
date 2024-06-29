document.getElementById('checkboxNoLabel').addEventListener('change', function () {
    // const addButton = document.getElementById('addButton');
    const editButton = document.getElementById('editButton');
    const deleteButton = document.getElementById('deleteButton');

    if (this.checked) {
      // addButton.disabled = false;
      editButton.disabled = false;
      deleteButton.disabled = false;
    } else {
      // addButton.disabled = true;
      editButton.disabled = true;
      deleteButton.disabled = true;
    }
  });