import Pusher from 'pusher-js';

Pusher.logToConsole = true;

const pusher = new Pusher('36bc312ce0c230ab69bf', {
    cluster: 'ap1',
    encrypted: true
});

export default pusher;
