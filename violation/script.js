
// Modal functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add Student Modal
    const addStudentBtn = document.getElementById('addStudentBtn');
    const addStudentModal = document.getElementById('addStudentModal');
    const addStudentClose = addStudentModal.querySelector('.close');
    
    if (addStudentBtn) {
        addStudentBtn.addEventListener('click', function() {
            addStudentModal.style.display = 'block';
        });
        
        addStudentClose.addEventListener('click', function() {
            addStudentModal.style.display = 'none';
        });
    }
    
    // Add Violation Modal
    const addViolationBtn = document.getElementById('addViolationBtn');
    const addViolationModal = document.getElementById('addViolationModal');
    const addViolationClose = addViolationModal.querySelector('.close');
    
    if (addViolationBtn) {
        addViolationBtn.addEventListener('click', function() {
            addViolationModal.style.display = 'block';
        });
        
        addViolationClose.addEventListener('click', function() {
            addViolationModal.style.display = 'none';
        });
    }
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Close view violation modal
    const viewViolationModal = document.getElementById('viewViolationModal');
    if (viewViolationModal) {
        const viewViolationClose = viewViolationModal.querySelector('.close');
        viewViolationClose.addEventListener('click', function() {
            viewViolationModal.style.display = 'none';
        });
    }
    
    // Close update violation modal
    const updateViolationModal = document.getElementById('updateViolationModal');
    if (updateViolationModal) {
        const updateViolationClose = updateViolationModal.querySelector('.close');
        updateViolationClose.addEventListener('click', function() {
            updateViolationModal.style.display = 'none';
        });
    }
});

// Search functionality for tables
function searchTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tr');
    
    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            let found = false;
            
            for (let j = 0; j < row.cells.length; j++) {
                const cell = row.cells[j];
                if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });
}