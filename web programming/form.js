// Set current year
document.getElementById('current-year').textContent = new Date().getFullYear();

// File upload preview
const fileInput = document.getElementById('proof');
const filePreview = document.getElementById('filePreview');

fileInput.addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  
  filePreview.innerHTML = '';
  filePreview.style.display = 'block';
  
  if (file.type.startsWith('image/')) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      filePreview.appendChild(img);
      
      const fileName = document.createElement('p');
      fileName.textContent = file.name;
      filePreview.appendChild(fileName);
    };
    reader.readAsDataURL(file);
  } else {
    const fileInfo = document.createElement('div');
    fileInfo.innerHTML = `
      <i class="fas fa-file-alt" style="font-size: 2rem; color: var(--primary);"></i>
      <p>${file.name}</p>
      <small>${(file.size / 1024).toFixed(1)} KB</small>
    `;
    filePreview.appendChild(fileInfo);
  }
});

// Form submission
document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
  e.preventDefault();
  alert('Your maintenance request has been submitted successfully!');
  // In a real application, you would send the form data to the server here
});