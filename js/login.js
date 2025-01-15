document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const emailInput = document.querySelector('#email');
    const senhaInput = document.querySelector('#password');
    const submitButton = document.querySelector('.action-button');
    
    function validateForm(event) {
        let valid = true;
        
        if (emailInput.value === '') {
            emailInput.style.borderColor = '#FF4D4D'; 
            valid = false;
        } else {
            emailInput.style.borderColor = '#ccc'; 
        }
        
        if (senhaInput.value === '') {
            senhaInput.style.borderColor = '#FF4D4D'; 
            valid = false;
        } else {
            senhaInput.style.borderColor = '#ccc'; 
        }
        
        if (!valid) {
            event.preventDefault();
        }
    }

    form.addEventListener('submit', validateForm);
});
