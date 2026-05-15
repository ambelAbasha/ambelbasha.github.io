/*
Placed this file as: Scripts/WebGL/vanishingPoint.js

DESCRIPTION
- This script creates a Three.js scene where "cards" (planes) are placed along the Z axis.
- Scrolling (and arrow keys) move the camera forward so items appear to come from a vanishing point.
- Each card is textured by a generated canvas (you can replace these with image thumbnails of PPT/PDFs).
- Smooth easing/lerp ensures the motion feels polished.

*/

(function(){
  // Dynamic load of three.js if not present
  function loadThreeAndInit() {
    if (window.THREE) return init();
    const s = document.createElement('script');
    s.src = 'https://unpkg.com/three@0.152.2/build/three.min.js';
    s.onload = init;
    document.head.appendChild(s);
  }

  loadThreeAndInit();

  function init() {
    const THREE = window.THREE;
    // ---- Config ----
    const NUM_CARDS = 9; // change to match number of elements
    const SPACING = 700; // distance between cards on z-axis
    const START_Z = 1600; // starting camera z (further back)
    const END_Z = 200; // camera z when scrolled to bottom
    const VANISH_X = 0; // central vanishing point x
    const VANISH_Y = 0; // central vanishing point y

    // DOM
    const canvas = document.getElementById('webgl-canvas');
    if (!canvas) {
      console.warn('WebGL canvas (#webgl-canvas) not found — inserting one into body.');
      const view = document.createElement('section');
      view.id = 'webgl-viewport';
      view.className = 'webgl-viewport';
      const c = document.createElement('canvas'); c.id = 'webgl-canvas';
      view.appendChild(c);
      document.body.appendChild(view);
    }

    const renderer = new THREE.WebGLRenderer({ canvas: document.getElementById('webgl-canvas'), alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight - 68);
    renderer.domElement.style.display = 'block';

    const scene = new THREE.Scene();

    const camera = new THREE.PerspectiveCamera(55, window.innerWidth / (window.innerHeight - 68), 1, 8000);
    camera.position.set(0, 0, START_Z);
    camera.lookAt(0, 0, 0);

    // subtle ambient + directional
    const ambient = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambient);
    const dir = new THREE.DirectionalLight(0xffffff, 0.6);
    dir.position.set(0.5, 1, 0.5).normalize();
    scene.add(dir);

    // create cards
    const cards = [];
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    const cardGeometry = new THREE.PlaneGeometry(600, 380, 1, 1);

    function createCardTexture(title, index) {
      // create a canvas texture — replace this with real thumbnails when available
      const w = 1200, h = 760; // high res for crispness
      const c = document.createElement('canvas'); c.width = w; c.height = h;
      const ctx = c.getContext('2d');

      // background gradient
      const g = ctx.createLinearGradient(0, 0, w, h);
      const hue = (index * 35) % 360;
      g.addColorStop(0, `hsl(${hue}deg 70% 55% / 0.95)`);
      g.addColorStop(1, `hsl(${(hue + 60) % 360}deg 60% 40% / 0.95)`);
      ctx.fillStyle = g;
      ctx.fillRect(0, 0, w, h);

      // rounded card mask
      ctx.globalCompositeOperation = 'destination-in';
      const r = 40;
      ctx.fillStyle = 'black';
      ctx.beginPath();
      ctx.moveTo(r, 0);
      ctx.lineTo(w - r, 0);
      ctx.quadraticCurveTo(w, 0, w, r);
      ctx.lineTo(w, h - r);
      ctx.quadraticCurveTo(w, h, w - r, h);
      ctx.lineTo(r, h);
      ctx.quadraticCurveTo(0, h, 0, h - r);
      ctx.lineTo(0, r);
      ctx.quadraticCurveTo(0, 0, r, 0);
      ctx.closePath();
      ctx.fill();
      ctx.globalCompositeOperation = 'source-over';

      // title text
      ctx.fillStyle = 'rgba(255,255,255,0.95)';
      ctx.font = 'bold 56px Poppins, Roboto, sans-serif';
      ctx.textAlign = 'center';
      ctx.fillText(title, w / 2, h / 2 + 20);

      // subtle vignette
      const grad = ctx.createRadialGradient(w/2, h/2, 0, w/2, h/2, Math.max(w,h));
      grad.addColorStop(0, 'rgba(0,0,0,0)');
      grad.addColorStop(1, 'rgba(0,0,0,0.25)');
      ctx.fillStyle = grad;
      ctx.fillRect(0,0,w,h);

      const tex = new THREE.CanvasTexture(c);
      tex.minFilter = THREE.LinearFilter;
      tex.generateMipmaps = true;
      return tex;
    }

    for (let i = 0; i < NUM_CARDS; i++) {
      const mat = new THREE.MeshStandardMaterial({ map: createCardTexture(`Card ${i+1}`, i), transparent: true });
      const m = new THREE.Mesh(cardGeometry, mat);

      // spread cards in a fan around the vanishing point
      const angle = (i - (NUM_CARDS-1)/2) * 0.06; // slight horizontal fan
      const xOffset = Math.sin(angle) * 200; // horizontal offset
      const yOffset = Math.cos(angle) * -60 * Math.abs(i - (NUM_CARDS-1)/2) / ((NUM_CARDS-1)/2 + 1); // slight vertical stagger
      m.position.set(xOffset + VANISH_X, yOffset + VANISH_Y, -i * SPACING - 400);

      // rotate to face camera with small tilt
      m.rotation.y = angle;
      m.userData = { baseZ: m.position.z, baseX: m.position.x, baseY: m.position.y };

      cardGroup.add(m);
      cards.push(m);
    }

    // target camera z controlled by scroll / arrows
    let targetCamZ = START_Z;
    let currentCamZ = START_Z;

    // compute scrollable range mapping
    function getScrollProgress() {
      const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      if (maxScroll <= 0) return 0;
      return window.scrollY / maxScroll;
    }

    function updateTargetFromScroll() {
      const p = getScrollProgress(); // 0 .. 1
      // Map scroll progress to camera z range (START_Z -> END_Z)
      targetCamZ = START_Z - p * (START_Z - END_Z);
    }

    // arrow navigation
    let navIndex = 0;
    function goToIndex(index) {
      navIndex = Math.max(0, Math.min(NUM_CARDS - 1, index));
      // set targetCamZ so that the card at navIndex is approximately at z = 300 (close to camera)
      const targetZForCard = cards[navIndex].userData.baseZ;
      // camera should move so that card z relative to camera is near 400
      targetCamZ = targetZForCard + 400;
    }

    window.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { goToIndex(navIndex + 1); }
      if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { goToIndex(navIndex - 1); }
    });

    // update target based on scroll
    window.addEventListener('scroll', () => { updateTargetFromScroll(); });

    // responsive resize
    function onResize() {
      const h = window.innerHeight - 68;
      renderer.setSize(window.innerWidth, h);
      camera.aspect = window.innerWidth / h;
      camera.updateProjectionMatrix();
    }
    window.addEventListener('resize', onResize);

    // animation loop
    function animate() {
      requestAnimationFrame(animate);

      // smooth camera z lerp
      currentCamZ += (targetCamZ - currentCamZ) * 0.08; // easing
      camera.position.z = currentCamZ;

      // subtle parallax of cards depending on camera z
      for (let i = 0; i < cards.length; i++) {
        const c = cards[i];
        const dz = c.userData.baseZ - camera.position.z; // distance from camera
        const k = 1 / Math.max(1, Math.abs(dz) / 800); // scale factor when closer
        const targetScale = 0.6 + k * 1.2;
        c.scale.x += (targetScale - c.scale.x) * 0.06;
        c.scale.y += (targetScale - c.scale.y) * 0.06;

        // gently nudge position to simulate perspective intersection toward vanishing point
        const progress = THREE.MathUtils.clamp((START_Z - camera.position.z) / (START_Z - END_Z), 0, 1);
        c.position.x += (c.userData.baseX * (1 - progress) - c.position.x) * 0.06;
        c.position.y += (c.userData.baseY * (1 - progress) - c.position.y) * 0.06;

        // fade in when near front
        const alpha = THREE.MathUtils.clamp(1 - Math.abs(dz) / 1600, 0, 1);
        c.material.opacity = 0.2 + alpha * 0.9;
      }

      renderer.render(scene, camera);
    }

    // initial update and start loop
    updateTargetFromScroll();
    animate();

    // Public hook: replace a card texture with an image/thumbnail
    window.webglVP = {
      setCardTexture: function(i, img) {
        if (!cards[i]) return;
        const tex = new THREE.Texture(img);
        tex.needsUpdate = true;
        cards[i].material.map = tex;
        cards[i].material.needsUpdate = true;
      },
      goToIndex
    };

  }
})();
