document.addEventListener('DOMContentLoaded', () => {
    const countdownBtn = document.getElementById('countdownBtn');
    const countdown = document.getElementById('countdown');
    const raffleBtn = document.getElementById('raffleBtn');
    const winnerDiv = document.getElementById('winner');
    const loadPostsBtn = document.getElementById('loadPostsBtn');
    const postSelect = document.getElementById('post');
    const postForm = document.getElementById('postSelect');
    const commentsTable = document.getElementById('commentsTable');

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

    async function loadComments(mediaId) {
        const res = await fetch(`fetch_comments.php?id=${mediaId}`);
        const comments = await res.json();
        commentsTable.innerHTML = '<tr><th>Usuario</th><th>Comentario</th></tr>';
        for (const c of comments) {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${c.username}</td><td>${c.text}</td>`;
            commentsTable.appendChild(row);
        }
    }

    if (loadPostsBtn) {
        loadPostsBtn.addEventListener('click', async () => {
            const res = await fetch('fetch_posts.php');
            const posts = await res.json();
            postSelect.innerHTML = '';
            for (const p of posts) {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.caption || p.id;
                postSelect.appendChild(opt);
            }
            if (posts.length > 0) {
                postForm.style.display = '';
                countdownBtn.style.display = '';
                raffleBtn.style.display = '';
                loadComments(posts[0].id);
            }
        });
    }

    if (postSelect) {
        postSelect.addEventListener('change', () => {
            loadComments(postSelect.value);
        });
    }

    if (raffleBtn) {
        raffleBtn.addEventListener('click', async () => {
            winnerDiv.textContent = '';
            const res = await fetch(`raffle.php?post=${postSelect.value}`);
            const data = await res.json();
            if (data && data.name) {
                winnerDiv.innerHTML = `Ganador: ${data.name} - ${data.text} <br><a href="export.php?id=${data.id}">Descargar PDF</a>`;
            } else {
                winnerDiv.textContent = 'No hay comentarios';
            }
        });
    }
});

