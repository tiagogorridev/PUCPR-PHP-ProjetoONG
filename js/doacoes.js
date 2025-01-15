document.getElementById('filter-ong').addEventListener('change', filterDonations);

document.getElementById('start-date').addEventListener('change', filterDonations);
document.getElementById('end-date').addEventListener('change', filterDonations);

function filterDonations() {
    const selectedOng = document.getElementById('filter-ong').value;
    const startDate = document.getElementById('start-date').value;
    const endDate = document.getElementById('end-date').value;
    const donationBoxes = document.querySelectorAll('.donation-box');

    donationBoxes.forEach(box => {
        let show = true;
        const donationDate = box.getAttribute('data-date');
        const donationOng = box.getAttribute('data-ong');

        if (selectedOng !== 'all' && selectedOng !== donationOng) {
            show = false;
        }

        if (show && startDate && new Date(donationDate) < new Date(startDate)) {
            show = false;
        }
        if (show && endDate && new Date(donationDate) > new Date(endDate)) {
            show = false;
        }

        box.style.display = show ? 'block' : 'none';
    });
}