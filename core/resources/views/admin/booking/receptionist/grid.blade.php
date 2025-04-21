<div id="grid-main" class="scroll-grid">

</div>


<style scoped>
    .bg-yellow {
        background: #ebb579;
    }

    .text-white {
        color: white;
    }

    .bg-red {
        background: #e6454d;
    }
    .swal2-container {
        z-index: 9999999 !important;
    }
    #grid-main {
        width: 100%;
        height: 100vh;
        overflow: auto;
        white-space: nowrap;
        scrollbar-width: thin;
    }

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

    .menu-wrapper {
        position: relative;
        display: inline-block;
    }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border: 1px solid #ccc;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        display: none;
        z-index: 1000;
        min-width: 100px !important;
        border-radius: 4px;
        padding: 4px 0;
        font-size: 13px;
    }

    .dropdown-item {
        padding: 4px 10px;
        font-size: 12px;
        cursor: pointer;
        white-space: nowrap;
    }

    .menu-btn {
        cursor: pointer;

        font-size: 20px;

    }
</style>
