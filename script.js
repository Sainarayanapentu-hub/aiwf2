// Page navigation
function showPage(pageId) {
  document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
  document.getElementById(pageId).classList.add('active');
}

// Form validation
document.getElementById("registerForm").addEventListener("submit", function(event) {
  let contact = document.getElementById("contact").value;
  if (!/^[6-9]\d{9}$/.test(contact)) {
    alert("దయచేసి సరైన 10 అంకెల మొబైల్ నంబర్ నమోదు చేయండి");
    event.preventDefault();
  }
});
