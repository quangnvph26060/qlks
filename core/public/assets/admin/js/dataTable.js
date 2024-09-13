let debounceTimer;
let currentPage = 1;
let apiUrl = "";
let is_column_id = "";

// Hàm khởi tạo
function initDataFetch(url, columnId) {
    apiUrl = url; // Lưu URL vào biến toàn cục
    is_column_id = columnId;
    fetchData(); // Tải dữ liệu ban đầu
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
            is_column_id ? updateTableIndexes() : null;
            notData();
        },
    });
}

// Tìm kiếm
$(".searchInput").on("input", function () {
    clearTimeout(debounceTimer);
    const searchValue = $(this).val();

    if (searchValue === "") {
        // Khi ô tìm kiếm trống, gọi fetchData để lấy dữ liệu ban đầu
        fetchData(1);
    }
    debounceTimer = setTimeout(() => {
        fetchData(1); // Gọi fetchData nếu có giá trị tìm kiếm
    }, 500);
});

// Thay đổi số bản ghi trên trang
$(".perPage").on("change", function () {
    const perPage = parseInt($(this).val(), 10);
    const currentRecordCount = $(".total-records").html() ?? 0; // Sử dụng .length để đếm số hàng
    console.log(currentRecordCount + " records in total " + perPage);

    if (currentRecordCount < perPage) {
        // Nếu số hàng hiện có nhỏ hơn số bản ghi được yêu cầu, không gọi API
        return;
    }

    fetchData(1); // Gọi fetchData với trang 1 khi thay đổi số bản ghi
});

// Phân trang
$(document).on("click", ".pagination a", function (e) {
    e.preventDefault();
    let page = $(this).attr("href").split("page=")[1];
    fetchData(page);
});

// Hàm kiểm tra dữ liệu
function notData() {

    // Check if there are no rows in the tbody
    if ($(".table tbody tr").length === 0) {
        console.log("no data");

        // Calculate the number of columns
        var colspan = $(".table thead th").length;

        // Append the "No data" row
        $(".table tbody").append(
            `<tr id="no-data-row"><td colspan="${colspan}" class="text-center">Không tìm thấy dữ liệu</td></tr>`
        );
    } else {
        console.log("has data");

        // Remove the "No data" row if it exists
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
