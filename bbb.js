document.querySelectorAll('.sidebar-menu a').forEach(link => {
    // Ignore the logout button so it can actually log the user out!
    if(link.id !== 'logout') { 
        link.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Remove active classes
            document.querySelectorAll('.sidebar-menu a').forEach(a => a.classList.remove('active'));
            document.querySelectorAll('.section').forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked link
            link.classList.add('active');
            
            // Find the right section and display it
            const sectionId = link.getAttribute('data-section');
            if (sectionId) {
                document.getElementById(sectionId).classList.add('active');
            }
        });
    }
});