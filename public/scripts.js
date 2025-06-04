document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('countdownBtn');
    const display = document.getElementById('countdown');
    if (btn) {
        btn.addEventListener('click', () => {
            let count = 5;
            display.textContent = count;
            const interval = setInterval(() => {
                count -= 1;
                if (count <= 0) {
                    clearInterval(interval);
                    display.textContent = '¡Sorteo!';
                } else {
                    display.textContent = count;
                }
            }, 1000);
        });
    }
});

