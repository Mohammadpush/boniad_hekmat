// This file generates a simple notification beep sound using Web Audio API
// Run this in browser console to generate notification.mp3

const audioContext = new (window.AudioContext || window.webkitAudioContext)();
const sampleRate = audioContext.sampleRate;
const duration = 0.3; // 300ms
const frequency = 800; // 800Hz beep

const buffer = audioContext.createBuffer(1, sampleRate * duration, sampleRate);
const channelData = buffer.getChannelData(0);

for (let i = 0; i < buffer.length; i++) {
    const t = i / sampleRate;
    const envelope = Math.exp(-t * 5); // Exponential decay
    channelData[i] = Math.sin(2 * Math.PI * frequency * t) * envelope * 0.3;
}

const source = audioContext.createBufferSource();
source.buffer = buffer;
source.connect(audioContext.destination);
source.start();

console.log('Notification sound played. This is a generated beep sound.');
console.log('For production, replace with a proper audio file at: /assets/sounds/notification.mp3');
