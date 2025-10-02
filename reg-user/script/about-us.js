document.addEventListener('DOMContentLoaded', function() {
            const teamMembers = document.querySelectorAll('.team-member');
            
            teamMembers.forEach(member => {
                member.addEventListener('click', function(e) {
                    // Don't toggle if clicking on social links
                    if (e.target.classList.contains('social-icon')) {
                        return;
                    }
                    
                    const details = this.querySelector('.member-details');
                    const isActive = details.classList.contains('active');
                    
                    // Close all open details first
                    document.querySelectorAll('.member-details').forEach(d => {
                        d.classList.remove('active');
                    });
                    
                    // Open clicked one unless it was already active
                    if (!isActive) {
                        details.classList.add('active');
                    }
                });
            });
            
            // Smooth scroll for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
});