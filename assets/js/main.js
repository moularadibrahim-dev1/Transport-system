document.addEventListener('DOMContentLoaded', () => {
    
    // Sidebar Toggle Logic (for mobile)
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Create toggle button if it doesn't exist
    if (!document.querySelector('.menu-toggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'menu-toggle';
        toggleBtn.innerHTML = '<i class="fa fa-bars"></i>';
        toggleBtn.style.cssText = `
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: none; /* Hidden on desktop by default via CSS */
        `;
        document.body.appendChild(toggleBtn);
        
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });
    }

    // Dynamic Alert Dismissal
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";
            setTimeout(() => alert.remove(), 500);
        }, 5000); // Auto dismiss after 5 seconds
    });

    // Confirm Delete Actions globally (backup if inline onclick is missing)
    const deleteForms = document.querySelectorAll('form[action*="delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });

    // Table Row Highlight on Click (Optional UX enhancement)
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', (e) => {
            // Don't trigger if clicking a button/link inside the row
            if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'I') {
                tableRows.forEach(r => r.classList.remove('selected-row'));
                row.classList.add('selected-row');
            }
        });
    });
});
