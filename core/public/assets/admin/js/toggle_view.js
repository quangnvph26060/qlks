"use strict";

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
function toggleView(viewType) {
    localStorage.setItem('viewMode', viewType);
    updateActiveButton();
    loadRoomBookings(); // loading table
}
window.toggleRepresentatives = function(id, button) { 
    const currentView = localStorage.getItem('viewMode');
    if (currentView === 'viewBox') {
        const rows = document.querySelectorAll('[id="rep-' + id + '"]'); 
        rows.forEach(row => row.classList.toggle('show'));
        button.classList.toggle('collapsed');
    }
};

function updateActiveButton() {
    const savedView = localStorage.getItem('viewMode') || 'viewBox';
    document.querySelectorAll('.btn-toggle-view').forEach(button => {
        button.classList.remove('active');
    });

    document.querySelector(`.btn-toggle-view[data-view="${savedView}"]`).classList.add('active');
}

document.addEventListener('DOMContentLoaded', updateActiveButton);