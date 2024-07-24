const firstname = document.getElementById("firstName");
const lastname = document.getElementById("lastName");
const phone = document.getElementById('phone');
const form = document.querySelector('form');
const country = document.getElementById('country');





countries.forEach((countryName) => {
    const option = document.createElement('option');
    option.value = countryName;
    option.innerText = countryName;
    country.appendChild(option);
});

firstname.addEventListener('keydown', (e) => {
    const key = e.key;
    if (!/[a-zA-Z]/.test(key) && key !== 'Backspace') {
        e.preventDefault();
    }
});

lastname.addEventListener('keydown', (e) => {
    const key = e.key;
    if (!/[a-zA-Z]/.test(key) && key !== 'Backspace') {
        e.preventDefault();
    }
});

phone.addEventListener('keydown', (e) => {
    const key = e.key;
    if (e.key == 'Backspace' || /[0-9]/.test(key)) {
        return;
    } else {
        e.preventDefault();
    }
});

form.addEventListener('submit', (e) => {
    if (phone.value.length < 7 || phone.value.length > 15) {
        e.preventDefault();
        alertbox.render({
            alertIcon: 'warning',
            title: 'Note',
            message: 'Phone Number cannot be less than 7 digits or exceed 15 digits!',
            btnTitle: 'Ok',
            border: true
        });
    }
});