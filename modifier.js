const userId = target.getAttribute('data-id');
const field = target.getAttribute('data-field');
const newValue = prompt('Nouvelle valeur pour ' + field + ' :', target.innerText);

if (newValue !== null) {
    // Envoyer la mise à jour au serveur
    const formData = new FormData();
    formData.append('id', userId);
    formData.append('field', field);
    formData.append('newValue', newValue);

    fetch('Update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);  // Afficher la réponse du serveur
        if (data.startsWith('L\'utilisateur')) {
            target.innerText = newValue;
        }
    })
    .catch(error => console.error('Erreur :', error));
}
