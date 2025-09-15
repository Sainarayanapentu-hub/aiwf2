// Missing showPage() function is now defined
    function showPage(pageId) {
      // Hide all pages
      const pages = document.querySelectorAll('.page');
      pages.forEach(page => {
        page.classList.remove('active');
      });

      // Show the selected page
      const activePage = document.getElementById(pageId);
      if (activePage) {
        activePage.classList.add('active');
      }
    }

    const scriptURL = "https://script.google.com/macros/s/AKfycbxU0chw-cytE0g9QLhRlLcxhkcf4OFx3hcaQkab1adi7goY-Gx8f0-QdFfFH6xbKzap/exec";
    const form = document.getElementById("aiwf"); // Using a new ID for clarity
    const responseMessage = document.getElementById("response");

    form.addEventListener("submit", e => {
      e.preventDefault();
      
      // Correctly create FormData from the form
      const formData = new FormData(form);

      // Display a loading message
      responseMessage.textContent = "Processing...";
      responseMessage.style.color = "blue";
      
      fetch(scriptURL, { method: "POST", body: formData })
        .then(response => {
          if (response.ok) {
            responseMessage.textContent = "విజయవంతంగా సమర్పించబడింది - Submitted Successfully!";
            responseMessage.style.color = "green";
            form.reset(); // Clear the form on success
          } else {
            throw new Error('Network response was not ok');
          }
        })
        .catch(error => {
          console.error("Error!", error.message);
          responseMessage.textContent = "పొరపాటు జరిగింది:";
          responseMessage.style.color = "red";
        });
    });

// Page navigation
function showPage(pageId) {
  document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
  document.getElementById(pageId).classList.add('active');
}


