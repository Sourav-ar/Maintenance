// Set current year in footer
document.getElementById('current-year').textContent = new Date().getFullYear();

// Form submission
document.getElementById('suggestionForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Form data collection
  const formData = {
    regNo: document.getElementById('regNo').value,
    studentName: document.getElementById('studentName').value,
    block: document.getElementById('block').value,
    roomNo: document.getElementById('roomNo').value,
    improvementType: document.getElementById('improvementType').value,
    suggestion: document.getElementById('suggestion').value
  };
  
  console.log('Suggestion submitted:', formData);
  alert('Thank you for your suggestion! We appreciate your input.');
  this.reset();
});