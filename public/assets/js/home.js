import { signIn } from './services/home/authentication.service.js'
import { url } from './utils/globalVariables.js'

document.getElementById('sign-in-form').addEventListener('submit', async (event) => {
  event.preventDefault();

  const form = new FormData(event.target);
  const responseObject = await signIn(form, url);
})

/* const token = "your-jwt-token-here"; // Replace with a real token

fetch("http://your-backend.com/api/verify-token.php", {
    method: "GET",
    headers: {
        "Authorization": `Bearer ${token}`,
        "Content-Type": "application/json"
    }
})
.then(response => response.text()) // Assuming PHP returns plain text
.then(data => console.log("Server Response:", data))
.catch(error => console.error("Error:", error)); */