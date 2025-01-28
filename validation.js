document.querySelector('form').addEventListener('submit', function(e) {
    const name = document.getElementById('name');
    const firstname = document.getElementById('firstname');
    const email = document.getElementById('email');
    const file = document.getElementById('file');
    const description = document.getElementById('description');

    const nameError = document.getElementById('name-error');
    const firstnameError = document.getElementById('firstname-error');
    const emailError = document.getElementById('email-error');
    const descriptionError = document.getElementById('description-error');

    const nameRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const descriptionRegex = /^[A-Za-zÀ-ÿ0-9\s]{5,}$/;

    let isValid = true;

    // Réinitialiser les messages d'erreur
    nameError.textContent = '';
    firstnameError.textContent = '';
    emailError.textContent = '';
    descriptionError.textContent = '';

    // Validation du nom
    if (!nameRegex.test(name.value)) {
        nameError.textContent = 'Le nom n\'est pas valide';
        isValid = false;
    }

    // Validation du prénom
    if (!nameRegex.test(firstname.value)) {
        firstnameError.textContent = 'Le prénom n\'est pas valide';
        isValid = false;
    }

    // Validation de l'email
    if (!emailRegex.test(email.value)) {
        emailError.textContent = 'L\'email n\'est pas valide';
        isValid = false;
    }

    // Validation de la description
    if (!descriptionRegex.test(description.value)) {
        descriptionError.textContent = 'La description doit contenir au moins 5 caractères alphanumériques.';
        isValid = false;
    }

    // Empêcher la soumission du formulaire si une validation échoue
    if (!isValid) {
        e.preventDefault();
    }
});