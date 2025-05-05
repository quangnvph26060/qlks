
<div class="table-container">
    <table class="table   table-bordered table-calendar">
        <thead>
            <tr id="headerRow">
                <th>Phòng</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>
    <div id="realtimeTime" class="realtime-time"></div>
    <div id="realtimeLine" class="realtime-line"></div>
</div>
@push('script-lib')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>
    {{-- <script src="{{ asset('assets/admin/js/common.js') }}"></script> --}}
@endpush


<style scoped>
    .table-container {
        position: relative;
    }
#headerRow{
    background: #004C87;
    color: white;
}
    .realtime-line {
        position: absolute;
        top: 30px;
        bottom: 0;
        width: 2px;
        background-color: red;
        z-index: 10;
        display: none;
    }

    .realtime-time {
        position: absolute;
        background-color: red;
        color: white;
        padding: 2px 5px;
        font-size: 12px;
        border-radius: 3px;
        white-space: nowrap;
        cursor: pointer;
        transform: translateX(-50%);
        top: 7px;
        z-index: 999;
    }
    .td-split div:hover {
        background: rgba(0, 0, 255, 0.1);
    }

    /* .room_book {
        background-color: rgba(255, 165, 0, 0.5) !important;
        transition: background-color 0.5s ease-in-out;
    }

    .check_in_room {
        background-color: #d9585e !important;
        transition: background-color 0.5s ease-in-out;
    } */

    .highlight-hour {
        background-color: rgba(0, 123, 255, 0.5) !important;
    }

    #calendarView {
        max-height: 450px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
        height: 100vh;
    }

    .highlight-hour,
    .room_book,
    .check_in_room {
        /* border-left: none !important;
        border-right: none !important; */
    }
    .highlight-hour:hover,
        .room_book:hover,
        .check_in_room:hover {
            filter: brightness(1.2);
        }
   
</style>
