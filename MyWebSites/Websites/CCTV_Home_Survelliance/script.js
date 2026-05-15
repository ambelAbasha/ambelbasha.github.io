document.getElementById('enlargeButton').addEventListener('click', enlargeVideo);

function enlargeVideo() {
    var videoFrame = document.getElementById('videoFrame');
    videoFrame.style.position = 'fixed';
    videoFrame.style.top = '0';
    videoFrame.style.left = '0';
    videoFrame.style.width = '100vw';
    videoFrame.style.height = '100vh';
    videoFrame.style.zIndex = '9999';

    // Open video in fullscreen on a new page
    window.open(videoFrame.src, '_blank', 'fullscreen=yes');
}
