<div class="flex items-center mx-4" x-data="{
        time: '',
        init() {
            this.updateTime();
            setInterval(() => this.updateTime(), 1000);
        },
        updateTime() {
            const now = new Date();
            this.time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }) + ' WIB';
        }
    }">
    <span class="text-sm font-bold text-primary-500 dark:text-primary-400 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-lg" x-text="time"></span>
</div>
