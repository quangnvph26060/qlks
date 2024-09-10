let debounceTimer;
let currentPage = 1;
let apiUrl = "";

// Hàm khởi tạo
function initDataFetch(url) {
    apiUrl = url; // Lưu URL vào biến toàn cục
    fetchData(); // Tải dữ liệu ban đầu
}

function checkNotData() {
    if ($("#no-data-row").length <= 0) {
        fetchData();
    }
}

function fetchData(page = 1) {
    currentPage = page;
    const search = $(".searchInput").val();
    const perPage = $(".perPage").val();

    $.ajax({
        url: apiUrl,
        method: "GET",
        data: {
            search,
            page,
            perPage,
        },
        success: function (data) {
            $("#data-table tbody").html(data.results);
            $("#pagination").html(data.pagination);
            updateTableIndexes();
            notData();
        },
    });
}

// Tìm kiếm
$(".searchInput").on("input", function () {
    clearTimeout(debounceTimer);
    const searchValue = $(this).val();
    if (searchValue === "") {
        checkNotData();
    }
    debounceTimer = setTimeout(() => {
        checkNotData();
    }, 500);
});

// Thay đổi số bản ghi trên trang
$(".perPage").on("change", function () {
    checkNotData();
});

// Phân trang
$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    let page = $(this).attr("href").split("page=")[1];
    fetchData(page);
});

// Hàm kiểm tra dữ liệu
function notData() {
    if ($(".table tbody tr").length == 0) {
        $(".table tbody").append(
            '<tr id="no-data-row"><td colspan="6" class="text-center">@lang("Không tìm thấy dữ liệu")</td></tr>'
        );
    } else {
        $("#no-data-row").remove();
    }
}

const showSwalMessage = (icon, title, timer = 2000) => {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        },
    });
    Toast.fire({
        icon: icon,
        title: `<p>${title}</p>`,
    });
};

// Cập nhật lại số thứ tự cho các hàng
const updateTableIndexes = () => {
    const perPage = parseInt($(".perPage").val());
    $("table tbody tr").each(function (index) {
        const rowIndex = (currentPage - 1) * perPage + index + 1;
        $(this).find("td:first").text(rowIndex);
    });
};
