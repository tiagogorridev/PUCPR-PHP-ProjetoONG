document.getElementById('filter-ong').addEventListener('change', validateFilters);
document.getElementById('start-date').addEventListener('change', validateFilters);
document.getElementById('end-date').addEventListener('change', validateFilters);

function validateFilters() {
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;

    if (startDate && endDate && !isValidDateRange(startDate, endDate)) {
        showErrorMessage('A data de início não pode ser posterior à data de término.');
        return; 
    }

    filterDonations();
}

function isValidDateRange(startDate, endDate) {
    const start = new Date(startDate);
    const end = new Date(endDate);
    return start <= end;
}

function filterDonations() {
    const selectedOng = document.getElementById('filter-ong').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    const donationBoxes = document.querySelectorAll('.donation-box');

    let anyVisible = false; 

    donationBoxes.forEach(box => {
        let show = true;
        const donationDate = box.getAttribute('data-date');
        const donationOng = box.getAttribute('data-ong');

        if (selectedOng !== 'all' && selectedOng !== donationOng) {
            show = false;
        }

        if (startDate && new Date(donationDate) < new Date(startDate)) {
            show = false;
        }
        if (endDate && new Date(donationDate) > new Date(endDate)) {
            show = false;
        }

        box.style.display = show ? 'block' : 'none';
        if (show) anyVisible = true; 
    });

    if (!anyVisible) {
        showErrorMessage('Nenhuma doação encontrada para os filtros selecionados.');
    }
}

function showErrorMessage(message) {
    const errorMessageDiv = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    errorText.textContent = message;
    errorMessageDiv.style.display = 'block';
}

function hideErrorMessage() {
    const errorMessageDiv = document.getElementById('error-message');
    errorMessageDiv.style.display = 'none';
}

document.getElementById('filter-ong').addEventListener('change', hideErrorMessage);
document.getElementById('start-date').addEventListener('change', hideErrorMessage);
document.getElementById('end-date').addEventListener('change', hideErrorMessage);
