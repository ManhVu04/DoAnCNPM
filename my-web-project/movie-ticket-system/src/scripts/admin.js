// admin.js

// Function to handle admin login
function adminLogin(event) {
    event.preventDefault();
    const username = document.getElementById('admin-username').value;
    const password = document.getElementById('admin-password').value;

    // Perform login logic here (e.g., API call)
    console.log('Admin Login:', username, password);
}

// Function to add a new movie
function addMovie(movieData) {
    // Logic to add a movie (e.g., API call)
    console.log('Adding movie:', movieData);
}

// Function to delete a movie
function deleteMovie(movieId) {
    // Logic to delete a movie (e.g., API call)
    console.log('Deleting movie with ID:', movieId);
}

// Function to edit a movie
function editMovie(movieId, updatedData) {
    // Logic to edit a movie (e.g., API call)
    console.log('Editing movie with ID:', movieId, 'Updated Data:', updatedData);
}

// Event listeners
document.getElementById('admin-login-form').addEventListener('submit', adminLogin);