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
  function loadScript(src, integrity, crossOrigin) {
    return new Promise((resolve, reject) => {
      // avoid double insertion
      if (document.querySelector(`script[src="${src}"]`)) return resolve();
      const s = document.createElement('script');
      s.src = src;
      if (crossOrigin) s.crossOrigin = crossOrigin;
      s.onload = () => resolve();
      s.onerror = () => reject(new Error('Failed to load ' + src));
      document.head.appendChild(s);
    });
  }

  async function loadThreeAndInit() {
    try {
      if (!window.THREE) {
        await loadScript('https://unpkg.com/three@0.152.2/build/three.min.js');
      }
      // load non-module CSS3DRenderer
      if (!window.CSS3DRenderer) {
        await loadScript('https://unpkg.com/three@0.152.2/examples/js/renderers/CSS3DRenderer.js');
      }
      init();
    } catch (err) {
      console.error('Failed to load Three.js or CSS3DRenderer:', err);
    }
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

    // Ensure a viewport wrapper exists
    let viewport = document.getElementById('webgl-viewport');
    if (!viewport) {
      viewport = document.createElement('section');
      viewport.id = 'webgl-viewport';
      viewport.className = 'webgl-viewport';
      document.body.appendChild(viewport);
    }

    // create and/or find canvas for WebGL (visual cards)
    let webglCanvas = document.getElementById('webgl-canvas');
    if (!webglCanvas) {
      webglCanvas = document.createElement('canvas');
      webglCanvas.id = 'webgl-canvas';
      viewport.appendChild(webglCanvas);
    }

    // CSS3D renderer (for real DOM interactive card)
    const cssRenderer = new CSS3DRenderer();
    cssRenderer.domElement.style.position = 'absolute';
    cssRenderer.domElement.style.top = '0';
    cssRenderer.domElement.style.left = '0';
    cssRenderer.domElement.style.width = '100%';
    cssRenderer.domElement.style.height = '100%';
    cssRenderer.domElement.style.pointerEvents = 'auto'; // allow interaction
    cssRenderer.domElement.id = 'css3d-root';
    viewport.appendChild(cssRenderer.domElement);

    // WebGL renderer (for textured planes)
    const renderer = new THREE.WebGLRenderer({ canvas: webglCanvas, alpha: true, antialias: true });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setSize(window.innerWidth, window.innerHeight - 68);
    renderer.domElement.style.display = 'block';
    renderer.domElement.style.pointerEvents = 'none'; // let CSS3D handle pointer events

    const scene = new THREE.Scene();
    const cssScene = new THREE.Scene(); // separate scene for CSS3D objects

    const camera = new THREE.PerspectiveCamera(55, window.innerWidth / (window.innerHeight - 68), 1, 8000);
    camera.position.set(0, 0, START_Z);
    camera.lookAt(0, 0, 0);

    // subtle ambient + directional for WebGL
    const ambient = new THREE.AmbientLight(0xffffff, 0.6);
    scene.add(ambient);
    const dir = new THREE.DirectionalLight(0xffffff, 0.6);
    dir.position.set(0.5, 1, 0.5).normalize();
    scene.add(dir);

    // create textured WebGL cards (cards 2..N)
    const cards = [];
    const cardGroup = new THREE.Group();
    scene.add(cardGroup);

    const cardGeometry = new THREE.PlaneGeometry(600, 380, 1, 1);

    function createCardTexture(title, index) {
      const w = 1200, h = 760;
      const c = document.createElement('canvas'); c.width = w; c.height = h;
      const ctx = c.getContext('2d');
      const g = ctx.createLinearGradient(0, 0, w, h);
      const hue = (index * 35) % 360;
      g.addColorStop(0, `hsl(${hue}deg 70% 55% / 0.95)`);
      g.addColorStop(1, `hsl(${(hue + 60) % 360}deg 60% 40% / 0.95)`);
      ctx.fillStyle = g; ctx.fillRect(0,0,w,h);
      ctx.fillStyle = 'rgba(255,255,255,0.95)'; ctx.font = 'bold 56px Poppins, Roboto, sans-serif'; ctx.textAlign = 'center'; ctx.fillText(title, w/2, h/2+20);
      const tex = new THREE.CanvasTexture(c); tex.minFilter = THREE.LinearFilter; tex.generateMipmaps = true; return tex;
    }

    // Build WebGL planes for indexes 1..NUM_CARDS-1 (we'll reserve index 0 for CSS3D card)
    for (let i = 1; i < NUM_CARDS; i++) {
      const mat = new THREE.MeshStandardMaterial({ map: createCardTexture(`Card ${i+1}`, i), transparent: true });
      const m = new THREE.Mesh(cardGeometry, mat);
      const idx = i - 1; // relative index for spacing
      const angle = (idx - (NUM_CARDS-2)/2) * 0.06;
      const xOffset = Math.sin(angle) * 200;
      const yOffset = Math.cos(angle) * -60 * Math.abs(idx - (NUM_CARDS-2)/2) / ((NUM_CARDS-2)/2 + 1);
      m.position.set(xOffset + VANISH_X, yOffset + VANISH_Y, -(i) * SPACING - 400);
      m.rotation.y = angle;
      m.userData = { baseZ: m.position.z, baseX: m.position.x, baseY: m.position.y };
      cardGroup.add(m); cards.push(m);
    }

    // Create the interactive HTML card (index 0) using CSS3D
    // We'll clone your existing navbar + hero into a wrapper if present, otherwise create an example fallback
    let interactiveElement = null;
    const navbar = document.querySelector('.landing-navbar');
    const hero = document.querySelector('.hero');
    if (navbar && hero) {
      // clone to avoid moving original DOM out of flow
      const wrapper = document.createElement('div');
      wrapper.className = 'card-3d-html-wrapper';
      // Basic styles to make it appear like a centered card — you can override with CSS
      wrapper.style.width = '900px';
      wrapper.style.height = '560px';
      wrapper.style.boxSizing = 'border-box';
      wrapper.style.borderRadius = '20px';
      wrapper.style.overflow = 'hidden';
      wrapper.style.background = 'rgba(255,255,255,0.02)';
      wrapper.style.backdropFilter = 'blur(6px)';
      wrapper.style.boxShadow = '0 10px 30px rgba(0,0,0,0.35)';

      // clone and append copies of navbar and hero content
      wrapper.appendChild(navbar.cloneNode(true));
      wrapper.appendChild(hero.cloneNode(true));
      interactiveElement = wrapper;
    } else {
      // fallback simple content
      const fallback = document.createElement('div');
      fallback.style.width = '900px'; fallback.style.height = '560px'; fallback.style.display='flex'; fallback.style.alignItems='center'; fallback.style.justifyContent='center'; fallback.style.borderRadius='20px'; fallback.style.background='linear-gradient(135deg,#0FA4AF44,#02495044)';
      fallback.innerHTML = '<div style="color:white;font-size:24px;padding:20px;text-align:center">Interactive Card — place .landing-navbar and .hero in DOM to clone them here</div>';
      interactiveElement = fallback;
    }

    // Create CSS3DObject and position it at the front-most card position
    const cssObject = new CSS3DObject(interactiveElement);
    // initial placement similar to other cards: index 0
    cssObject.position.set(VANISH_X, VANISH_Y, -0 * SPACING - 400);
    cssObject.rotation.y = 0;
    cssObject.scale.set(0.8,0.8,0.8);
    cssObject.userData = { baseZ: cssObject.position.z, baseX: cssObject.position.x, baseY: cssObject.position.y };
    cssScene.add(cssObject);

    // target camera z controlled by scroll / arrows
    let targetCamZ = START_Z;
    let currentCamZ = START_Z;

    function getScrollProgress() {
      const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      if (maxScroll <= 0) return 0;
      return window.scrollY / maxScroll;
    }

    function updateTargetFromScroll() {
      const p = getScrollProgress();
      targetCamZ = START_Z - p * (START_Z - END_Z);
    }

    let navIndex = 0; // navIndex 0 corresponds to cssObject
    function goToIndex(index) {
      navIndex = Math.max(0, Math.min(NUM_CARDS - 1, index));
      // card z for index
      const cardZ = (navIndex === 0) ? cssObject.userData.baseZ : cards[navIndex-1].userData.baseZ;
      targetCamZ = cardZ + 400;
    }

    window.addEventListener('keydown', (e)=>{
      if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { goToIndex(navIndex + 1); }
      if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { goToIndex(navIndex - 1); }
    });
    window.addEventListener('scroll', ()=>{ updateTargetFromScroll(); });

    function onResize() {
      const h = window.innerHeight - 68;
      renderer.setSize(window.innerWidth, h);
      camera.aspect = window.innerWidth / h;
      camera.updateProjectionMatrix();
      cssRenderer.setSize(window.innerWidth, h);
    }
    window.addEventListener('resize', onResize);

    // animation loop: update both renderers
    function animate() {
      requestAnimationFrame(animate);
      currentCamZ += (targetCamZ - currentCamZ) * 0.08;
      camera.position.z = currentCamZ;

      // update webgl cards
      for (let i = 0; i < cards.length; i++) {
        const c = cards[i];
        const dz = c.userData.baseZ - camera.position.z;
        const k = 1 / Math.max(1, Math.abs(dz) / 800);
        const targetScale = 0.6 + k * 1.2;
        c.scale.x += (targetScale - c.scale.x) * 0.06;
        c.scale.y += (targetScale - c.scale.y) * 0.06;
        const progress = THREE.MathUtils.clamp((START_Z - camera.position.z) / (START_Z - END_Z), 0, 1);
        c.position.x += (c.userData.baseX * (1 - progress) - c.position.x) * 0.06;
        c.position.y += (c.userData.baseY * (1 - progress) - c.position.y) * 0.06;
        const alpha = THREE.MathUtils.clamp(1 - Math.abs(dz) / 1600, 0, 1);
        c.material.opacity = 0.2 + alpha * 0.9;
      }

      // update css object scaling/position based on camera
      const dzCss = cssObject.userData.baseZ - camera.position.z;
      const kCss = 1 / Math.max(1, Math.abs(dzCss) / 800);
      const targetScaleCss = 0.6 + kCss * 1.2;
      cssObject.scale.x += (targetScaleCss - cssObject.scale.x) * 0.06;
      cssObject.scale.y += (targetScaleCss - cssObject.scale.y) * 0.06;
      const progressCss = THREE.MathUtils.clamp((START_Z - camera.position.z) / (START_Z - END_Z), 0, 1);
      cssObject.position.x += (cssObject.userData.baseX * (1 - progressCss) - cssObject.position.x) * 0.06;
      cssObject.position.y += (cssObject.userData.baseY * (1 - progressCss) - cssObject.position.y) * 0.06;

      // Render both scenes using the same camera
      renderer.render(scene, camera);
      cssRenderer.render(cssScene, camera);
    }

    updateTargetFromScroll();
    animate();

    // Public hook to set webgl card textures or navigate
    window.webglVP = {
      setCardTexture: function(i,img){ if (!cards[i-1]) return; const tex = new THREE.Texture(img); tex.needsUpdate=true; cards[i-1].material.map=tex; cards[i-1].material.needsUpdate=true; },
      goToIndex
    };

  }
})();
