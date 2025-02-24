"use strict";
function updatePagination(pagination, loadFunction) {
    var paginationHtml = '';

    // Nút "Trước"
    if (pagination.current_page > 1) {
        paginationHtml += `<button onclick="${loadFunction}(${pagination.current_page - 1})">Trước</button>`;
    }

    // Tạo danh sách trang
    for (var i = 1; i <= pagination.last_page; i++) {
        paginationHtml += `
            <button onclick="${loadFunction}(${i})" 
                class="${pagination.current_page === i ? 'active' : ''}">
                ${i}
            </button>
        `;
    }

    // Nút "Tiếp theo"
    if (pagination.current_page < pagination.last_page) {
        paginationHtml += `<button onclick="${loadFunction}(${pagination.current_page + 1})">Tiếp theo</button>`;
    }

    $('.pagination-container').html(paginationHtml);
}
