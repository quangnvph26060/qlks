
<div id="grid-main" class="scroll-grid">

</div>

@push('scripts-book')
    <script src="{{ asset('assets/admin/js/grid.js') }}"></script>
  

@endpush

<style scoped>
#grid-main {
    width: 100%;
    height: 500px; /* Hoặc điều chỉnh theo nhu cầu */
    overflow: auto; /* Kích hoạt thanh cuộn */
    white-space: nowrap; /* Ngăn nội dung xuống dòng */
    scrollbar-width: thin; /* Thanh cuộn mỏng */
}

/* Scrollbar đẹp hơn (Chỉ áp dụng cho Chrome & Edge) */
#grid-main::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
#grid-main::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}
#grid-main::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.4);
}

</style>