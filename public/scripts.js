document.addEventListener('DOMContentLoaded', () => {
    const countdownBtn = document.getElementById('countdownBtn');
    const countdown = document.getElementById('countdown');
    const raffleBtn = document.getElementById('raffleBtn');
    const winnerDiv = document.getElementById('winner');

    if (countdownBtn) {
        countdownBtn.addEventListener('click', () => {
            let count = 5;
            countdown.textContent = count;
            const interval = setInterval(() => {
                count -= 1;
                if (count <= 0) {
                    clearInterval(interval);
                    countdown.textContent = '¡Sorteo!';
                } else {
                    countdown.textContent = count;
                }
            }, 1000);
        });
    }

    if (raffleBtn) {
        raffleBtn.addEventListener('click', async () => {
            winnerDiv.textContent = '';
            const res = await fetch('raffle.php');
            const data = await res.json();
            if (data && data.name) {
                winnerDiv.innerHTML = `Ganador: ${data.name} - ${data.text} <br><a href="export.php?id=${data.id}">Descargar PDF</a>`;
            } else {
                winnerDiv.textContent = 'No hay comentarios';
            }
        });
    }
});

