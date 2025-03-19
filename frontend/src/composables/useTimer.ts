import { ref, onUnmounted } from 'vue';

export function useTimer() {
    const seconds = ref(0);
    const isRunning = ref(false);
    let interval: any = null;

    const start = (initialSeconds = 0) => {
        seconds.value = initialSeconds;
        isRunning.value = true;

        interval = setInterval(() => {
            seconds.value++;
        }, 1000);
    };

    const stop = () => {
        isRunning.value = false;
        clearInterval(interval);
        interval = null;
    };

    const reset = () => {
        stop();
        seconds.value = 0;
    };

    onUnmounted(() => {
        if (interval) clearInterval(interval);
    });

    return {
        seconds,
        isRunning,
        start,
        stop,
        reset
    };
}
