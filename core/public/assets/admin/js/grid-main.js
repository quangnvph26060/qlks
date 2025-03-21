
let roomHTML = '';
let gridView = $('#grid-main');
function formatCurrency(amount) {
    if (!amount || isNaN(amount)) {
        return '0 VND'; // Nếu amount không hợp lệ, trả về 0 VND
    }

    const parts = parseFloat(amount).toFixed(2).toString().split('.');
    const integerPart = parts[0];
    const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    return formattedInteger + ' VND';
}
$.ajax({
    type: "GET",
    url: roomBoookingHistory,
    success: function (response) {
        if (response.status === 'success') {
            // resolve(response.data);
            let groupedRooms = {};

            response.data.forEach(item => {
                if (!groupedRooms[item.room_type_id]) {
                    groupedRooms[item.room_type_id] = {
                        name: item.room_type.name,
                        code: item.room_type_id,
                        rooms: []
                    };
                }
                groupedRooms[item.room_type_id].rooms.push(item);
            });

            let roomHTML = "";
            Object.values(groupedRooms).forEach(group => {
                roomHTML += `
                        <div class="floor-header" onclick="toggleFloor('${group.code}')">
                            ${group.name} (${group.rooms.length}) <span><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div id="${group.code}" class="room-grid">
                    `;
                //     <div class="time-info">
                //     <span class="clock-icon">🕒</span> 97 giờ 11 phút / 1 giờ
                // </div>
                group.rooms.forEach(item => {
                    roomHTML += `
                        <div class="room-card ${item.status}">
                            <div class="room-header">
                            <span class="status">${item.is_clean ? "✨ Sạch" : "🚨 Chưa dọn"}</span>
                                <span class="menu">⋮</span>
                            </div>
                            <div class="room-number">${item.room_number}</div>
                            <div class="room-info">${item.customer_type || "Không có thông tin"}</div>
                            <div class="time-info">
                                    ${formatCurrency(item.room_type.room_type_price.unit_price)}
                            </div>      

                        </div>
                    `;
                });

                roomHTML += `</div>`;
            });

            // Đổ vào HTML
            $("#grid-main").html(roomHTML);

        } else {
            reject("Không lấy được dữ liệu");
        }
    },
    error: function (error) {
        reject(error);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const savedView = localStorage.getItem('selectedView') || 'list';
    console.log(savedView);

    // Logic JS cho giao diện dạng Lưới
    document.querySelectorAll(".grid-item").forEach(item => {
        item.addEventListener("click", function () {
            alert("Bạn đã chọn một mục trong Grid!");
        });
    });
});

function toggleFloor(id) {
    var floor = document.getElementById(id);
    var icon = floor.previousElementSibling.querySelector("i");
    if (floor.style.display === "none") {
        floor.style.display = "grid";
        icon.classList.remove("fa-chevron-right");
        icon.classList.add("fa-chevron-down");
    } else {
        floor.style.display = "none";
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-right");
    }
}
