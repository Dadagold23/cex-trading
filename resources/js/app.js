import axios from 'axios';
import AOS from 'aos';
import 'aos/dist/aos.css';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 60,
        delay: 0,
    });

    const clickAudio = new Audio('/sounds/click.mp3');
    clickAudio.preload = 'auto';
    clickAudio.volume = 0.35;

    const playClickSound = () => {
        try {
            clickAudio.currentTime = 0;
            clickAudio.play().catch(() => {});
        } catch (e) {
            console.warn('Click sound failed:', e);
        }
    };

    document.addEventListener('click', (event) => {
        const target = event.target.closest(
            'button, .animated-button, a[data-click-sound="true"], input[type="submit"], input[type="button"]'
        );

        if (target) {
            playClickSound();
        }
    });
});
