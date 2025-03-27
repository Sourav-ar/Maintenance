// Set current year
document.getElementById('current-year').textContent = new Date().getFullYear();

// File upload preview with animation
const fileInput = document.getElementById('proof');
const filePreview = document.getElementById('filePreview');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    // Clear and animate the preview container
    filePreview.innerHTML = '';
    filePreview.style.display = 'block';
    filePreview.style.opacity = '0';
    filePreview.style.transform = 'translateY(10px)';
    
    // Animate in the preview
    setTimeout(() => {
        filePreview.style.transition = 'all 0.3s ease';
        filePreview.style.opacity = '1';
        filePreview.style.transform = 'translateY(0)';
    }, 10);
    
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.opacity = '0';
            img.style.transform = 'scale(0.9)';
            img.style.transition = 'all 0.3s ease 0.1s';
            filePreview.appendChild(img);
            
            const fileName = document.createElement('p');
            fileName.textContent = file.name;
            fileName.style.opacity = '0';
            fileName.style.transform = 'translateY(5px)';
            fileName.style.transition = 'all 0.3s ease 0.2s';
            filePreview.appendChild(fileName);
            
            setTimeout(() => {
                img.style.opacity = '1';
                img.style.transform = 'scale(1)';
                fileName.style.opacity = '1';
                fileName.style.transform = 'translateY(0)';
            }, 100);
        };
        reader.readAsDataURL(file);
    } else {
        const fileInfo = document.createElement('div');
        fileInfo.innerHTML = `
            <i class="fas fa-file-alt" style="font-size: 2rem; color: var(--primary);"></i>
            <p>${file.name}</p>
            <small>${(file.size / 1024).toFixed(1)} KB</small>
        `;
        fileInfo.style.opacity = '0';
        fileInfo.style.transform = 'translateY(10px)';
        fileInfo.style.transition = 'all 0.3s ease';
        filePreview.appendChild(fileInfo);
        
        setTimeout(() => {
            fileInfo.style.opacity = '1';
            fileInfo.style.transform = 'translateY(0)';
        }, 100);
    }
});

// Form submission with animation
document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Create a submission feedback element
    const feedback = document.createElement('div');
    feedback.innerHTML = `
        <div style="
            background: rgba(0, 210, 146, 0.9);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        ">
            <i class="fas fa-check-circle" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
            <p>Your maintenance request has been submitted successfully!</p>
        </div>
    `;
    
    // Insert the feedback before the form
    this.parentNode.insertBefore(feedback, this.nextSibling);
    
    // Animate the feedback in
    setTimeout(() => {
        const feedbackBox = feedback.querySelector('div');
        feedbackBox.style.opacity = '1';
        feedbackBox.style.transform = 'translateY(0)';
    }, 10);
    
    // In a real application, you would send the form data to the server here
    // For demo purposes, we'll reset the form after 3 seconds
    setTimeout(() => {
        feedback.style.transition = 'all 0.3s ease';
        feedback.style.opacity = '0';
        feedback.style.transform = 'translateY(-20px)';
        setTimeout(() => feedback.remove(), 300);
        this.reset();
        filePreview.style.display = 'none';
    }, 3000);
});

// Add hover effect to all buttons for consistency
document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('mouseenter', () => {
        button.style.transform = 'translateY(-2px)';
    });
    button.addEventListener('mouseleave', () => {
        button.style.transform = 'translateY(0)';
    });
});