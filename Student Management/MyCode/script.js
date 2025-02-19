document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('loginModal').style.display = 'flex';
});

function login() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();

    if (username === 'admin' && password === 'admin') {
        document.getElementById('loginModal').style.display = 'none';
        document.getElementById('userForm').style.display = 'block';
    } else {
        document.getElementById('loginError').style.display = 'block';
    }
}

function submitForm(action) {
    const formData = new FormData(document.getElementById('userForm'));
    formData.append('action', action);

    fetch('process.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            if (data.data) {
                const results = data.data.map(row =>
                    `ID: ${row.id}\nName: ${row.NAME}\nAge: ${row.age}\nMobile: ${row.mobile}\nEmail: ${row.email}\nGender: ${row.gender}\nCity: ${row.city}`
                ).join('\n\n');
                showModal(`Search Results:\n\n${results}`);
            } else {
                showModal(data.message);
            }
        } else {
            showModal(data.message);
        }
    })
    .catch(error => {
        showModal('An error occurred: ' + error);
    });
}

function showModal(message) {
    document.getElementById('modalMessage').innerText = message;
    document.getElementById('feedbackModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('feedbackModal').style.display = 'none';
}
