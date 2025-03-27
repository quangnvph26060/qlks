<div id="grid-main" class="scroll-grid">

</div>


<style scoped>
    .text-yellow {
        color: #ebb579;
    }

    .text-red {
        color: #e6454d;
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
</style>
