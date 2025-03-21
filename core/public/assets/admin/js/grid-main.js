
console.log('123123');
let roomHTML = '';
let gridView = $('#gridView');
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
            console.log(groupedRooms);
            Object.values(groupedRooms).forEach(group => {
                roomHTML += `
                        <div class="floor-header" onclick="toggleFloor('${group.code}')">
                            ${group.name} <span><i class="fas fa-chevron-down"></i></span>
                        </div>
                        <div id="${group.code}" class="room-grid">
                    `;

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
                                <span class="clock-icon">🕒</span> 97 giờ 11 phút / 1 giờ
                            </div>
                        </div>
                    `;
                });

                roomHTML += `</div>`;
            });

            // Đổ vào HTML
            $("#gridView").html(roomHTML);

        } else {
            reject("Không lấy được dữ liệu");
        }
    },
    error: function (error) {
        reject(error);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    console.log("Lưới JS đã chạy!");

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
