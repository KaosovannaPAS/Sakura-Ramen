document.addEventListener('DOMContentLoaded', () => {
    const statusElement = document.getElementById('live-status');
    const queueElement = document.getElementById('live-queue');

    if (statusElement) {
        const socket = io('http://localhost:3000');

        socket.on('statusUpdate', (data) => {
            updateStatusUI(data);
        });
    }

    function updateStatusUI(data) {
        if (!statusElement) return;

        if (data.isOpen) {
            statusElement.textContent = 'Ouvert — ' + (data.currentService === 'midi' ? 'Service du midi' : 'Service du soir');
            statusElement.className = 'status-tag status-open';
            if (queueElement) {
                queueElement.textContent = `File d'attente actuelle : ${data.queueLength} min`;
                queueElement.style.display = 'block';
            }
        } else {
            statusElement.textContent = 'Fermé actuellement';
            statusElement.className = 'status-tag status-closed';
            if (queueElement) queueElement.style.display = 'none';
        }
    }
});
