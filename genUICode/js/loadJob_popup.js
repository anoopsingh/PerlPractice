// Called once when the dialog displays
function loadPopUpContent() {
   if(!parent.UserInterface) parent = window.opener;
    alert(parent.document.getElementById('popuptype').value);
        parent.UserInterface.loadListBx('objPopupList_available_elements,
                            {'obj':'loadJob',
                             'method':'loadListItems',
                             'listName': 'duttype'
                            });
        alert(parent.document.getElementById('popuptype').value);
}
