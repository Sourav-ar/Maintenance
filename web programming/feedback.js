// Set current year in footer
document.getElementById('current-year').textContent = new Date().getFullYear();

// Star rating functionality
const stars = document.querySelectorAll('.rating-stars i');
const ratingInput = document.getElementById('rating');

stars.forEach(star => {
  star.addEventListener('click', function() {
    const ratingValue = this.getAttribute('data-rating');
    ratingInput.value = ratingValue;
    
    stars.forEach((s, index) => {
      if (index < ratingValue) {
        s.classList.remove('far');
        s.classList.add('fas', 'active');
      } else {
        s.classList.remove('fas', 'active');
        s.classList.add('far');
      }
    });
  });
  
  // Hover effect
  star.addEventListener('mouseover', function() {
    const hoverValue = this.getAttribute('data-rating');
    stars.forEach((s, index) => {
      if (index < hoverValue) {
        s.classList.add('hover');
      } else {
        s.classList.remove('hover');
      }
    });
  });
  
  star.addEventListener('mouseout', function() {
    stars.forEach(s => s.classList.remove('hover'));
  });
});

// Form submission
document.getElementById('feedbackForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  // Validate rating
  if (!ratingInput.value) {
    alert('Please provide a rating by clicking the stars');
    return;
  }
  
  // Form data collection
  const formData = {
    regNo: document.getElementById('regNo').value,
    studentName: document.getElementById('studentName').value,
    block: document.getElementById('block').value,
    roomNo: document.getElementById('roomNo').value,
    feedback: document.getElementById('feedback').value,
    rating: ratingInput.value
  };
  
  console.log('Feedback submitted:', formData);
  alert('Thank you for your valuable feedback!');
  this.reset();
  
  // Reset stars
  stars.forEach(star => {
    star.classList.remove('fas', 'active');
    star.classList.add('far');
  });
  ratingInput.value = '';
});