/**
 * heroWebGL.js — "The Corridor"
 *
 * Seven milestone acts the camera visits as the user scrolls.
 *
 * Act layout (world-space Z):
 *   Act 0  z =   0   Entry / Intro
 *   Act A  z =  -9   About Me milestone
 *   Act B  z = -18   What I Bring milestone
 *   Act 1  z = -27   Skills constellation
 *   Act 2  z = -37   Security
 *   Act 3  z = -47   CTA convergence
 *   Act C  z = -56   Footer / Connect milestone
 *
 * Camera: z = +3 → z = -59  over 700 vh of scroll (range = 62).
 */

(function () {
  'use strict';

  const CDN = 'https://unpkg.com/three@0.152.2/build/three.min.js';

  function boot() {
    if (window.THREE) { init(); return; }
    const s = document.createElement('script');
    s.src = CDN; s.onload = init; s.onerror = () => {};
    document.head.appendChild(s);
  }
  document.readyState === 'loading'
    ? document.addEventListener('DOMContentLoaded', boot)
    : boot();

  function smoothstep(a, b, t) {
    const x = Math.max(0, Math.min((t - a) / (b - a), 1));
    return x * x * (3 - 2 * x);
  }
  function lerp(a, b, t) { return a + (b - a) * t; }

  function init() {
    const THREE    = window.THREE;
    const viewport = document.getElementById('webgl-viewport');
    const canvas   = document.getElementById('webgl-canvas');
    if (!THREE || !viewport || !canvas) return;

    const renderer = new THREE.WebGLRenderer({
      canvas, alpha: true, antialias: true,
      powerPreference: 'high-performance',
    });
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const scene  = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(52, 1, 0.1, 300);
    camera.position.set(0, 0, 3);

    function resize() {
      const W = window.innerWidth, H = window.innerHeight;
      renderer.setSize(W, H);
      camera.aspect = W / H;
      camera.updateProjectionMatrix();
    }
    resize();
    window.addEventListener('resize', resize, { passive: true });

    /* palette */
    const T = new THREE.Color(0x0FA4AF);  // teal
    const G = new THREE.Color(0xF5C74F);  // gold
    const V = new THREE.Color(0xB8C46E);  // green
    const W = new THREE.Color(0xffffff);  // white
    const D = new THREE.Color(0x024950);  // dark

    /* ════════════════════════════════════════════════════════════════════════
       ACT 0 — Intro  (z = 0)
       ════════════════════════════════════════════════════════════════════════ */
    const act0 = new THREE.Group();

    const icoMat = new THREE.MeshBasicMaterial({ color: T, wireframe: true, transparent: true, opacity: 0.48 });
    const ico = new THREE.Mesh(new THREE.IcosahedronGeometry(0.52, 1), icoMat);
    act0.add(ico);

    act0.add(new THREE.Mesh(
      new THREE.IcosahedronGeometry(0.30, 0),
      new THREE.MeshBasicMaterial({ color: D, transparent: true, opacity: 0.75 })
    ));

    const ring0Mat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.48, side: THREE.DoubleSide });
    const ring0 = new THREE.Mesh(new THREE.RingGeometry(0.70, 0.74, 80), ring0Mat);
    act0.add(ring0);

    const ring0bMat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.22, side: THREE.DoubleSide });
    const ring0b = new THREE.Mesh(new THREE.RingGeometry(0.92, 0.95, 80), ring0bMat);
    ring0b.rotation.x = Math.PI / 3;
    act0.add(ring0b);

    for (let i = 0; i < 12; i++) {
      const a = (i / 12) * Math.PI * 2;
      const buf = new Float32Array([0,0,0, Math.cos(a)*3.2, Math.sin(a)*3.2, 0]);
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(buf, 3));
      act0.add(new THREE.Line(geo, new THREE.LineBasicMaterial({
        color: i % 4 === 0 ? G : T, transparent: true, opacity: 0.07
      })));
    }

    act0.position.z = 0;
    scene.add(act0);

    /* ════════════════════════════════════════════════════════════════════════
       ACT A — About Me Milestone  (z = -9)
       Profile nexus: torus gateway + orbiting life-aspect nodes
       ════════════════════════════════════════════════════════════════════════ */
    const actAbout = new THREE.Group();

    /* core sphere */
    const aboutCoreMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.88 });
    const aboutCore = new THREE.Mesh(new THREE.SphereGeometry(0.28, 20, 20), aboutCoreMat);
    actAbout.add(aboutCore);

    const aboutGlowMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.14 });
    actAbout.add(new THREE.Mesh(new THREE.SphereGeometry(0.52, 20, 20), aboutGlowMat));

    /* main torus gateway */
    const aboutTorusMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.72 });
    const aboutTorus = new THREE.Mesh(new THREE.TorusGeometry(0.82, 0.028, 18, 90), aboutTorusMat);
    actAbout.add(aboutTorus);

    /* tilted secondary torus */
    const aboutTorus2Mat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.38 });
    const aboutTorus2 = new THREE.Mesh(new THREE.TorusGeometry(1.08, 0.016, 14, 72), aboutTorus2Mat);
    aboutTorus2.rotation.x = Math.PI / 3;
    actAbout.add(aboutTorus2);

    /* outer glow ring */
    const aboutRingMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.20, side: THREE.DoubleSide });
    const aboutOuterRing = new THREE.Mesh(new THREE.RingGeometry(1.30, 1.34, 72), aboutRingMat);
    actAbout.add(aboutOuterRing);

    /* 5 orbiting nodes representing: education, experience, skills, security, vision */
    const aboutOrbColors = [G, T, V, G, T];
    const aboutOrbiters = [];
    aboutOrbColors.forEach((col, i) => {
      const orb = new THREE.Mesh(
        new THREE.SphereGeometry(0.07, 10, 10),
        new THREE.MeshBasicMaterial({ color: col })
      );
      const ring = new THREE.Mesh(
        new THREE.RingGeometry(0.11, 0.14, 24),
        new THREE.MeshBasicMaterial({ color: col, transparent: true, opacity: 0.3, side: THREE.DoubleSide })
      );
      ring.position.copy(orb.position);
      aboutOrbiters.push({ mesh: orb, ring, idx: i });
      actAbout.add(orb, ring);
    });

    /* spokes from core to each orbiter slot */
    for (let i = 0; i < 5; i++) {
      const a = (i / 5) * Math.PI * 2;
      const px = Math.cos(a) * 1.0, py = Math.sin(a) * 0.62;
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(
        new Float32Array([0,0,0, px, py, 0]), 3
      ));
      actAbout.add(new THREE.Line(geo, new THREE.LineBasicMaterial({ color: G, transparent: true, opacity: 0.14 })));
    }

    actAbout.position.z = -9;
    scene.add(actAbout);

    /* ════════════════════════════════════════════════════════════════════════
       ACT B — What I Bring Milestone  (z = -18)
       Skill wheel: 7 nodes (one per skill) around a teal octahedron hub
       ════════════════════════════════════════════════════════════════════════ */
    const actDeliver = new THREE.Group();

    /* central hub */
    const deliverHubMat = new THREE.MeshBasicMaterial({ color: T, wireframe: true, transparent: true, opacity: 0.70 });
    const deliverHub = new THREE.Mesh(new THREE.OctahedronGeometry(0.28, 0), deliverHubMat);
    actDeliver.add(deliverHub);

    const deliverHubSolid = new THREE.Mesh(
      new THREE.OctahedronGeometry(0.18, 0),
      new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.45 })
    );
    actDeliver.add(deliverHubSolid);

    /* 7 skill nodes */
    const deliverNodeColors = [T, G, V, W, T, G, V];
    const deliverNodes = [];
    deliverNodeColors.forEach((col, i) => {
      const a = (i / 7) * Math.PI * 2;
      const r = 1.45;
      const node = new THREE.Mesh(
        new THREE.OctahedronGeometry(0.11, 0),
        new THREE.MeshBasicMaterial({ color: col, transparent: true, opacity: 0.90 })
      );
      node.position.set(Math.cos(a) * r, Math.sin(a) * r * 0.68, 0);
      deliverNodes.push(node);
      actDeliver.add(node);

      /* halo ring per node */
      const halo = new THREE.Mesh(
        new THREE.RingGeometry(0.16, 0.20, 24),
        new THREE.MeshBasicMaterial({ color: col, transparent: true, opacity: 0.28, side: THREE.DoubleSide })
      );
      halo.position.copy(node.position);
      actDeliver.add(halo);

      /* spoke hub → node */
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(
        new Float32Array([0,0,0, node.position.x, node.position.y, node.position.z]), 3
      ));
      actDeliver.add(new THREE.Line(geo, new THREE.LineBasicMaterial({ color: T, transparent: true, opacity: 0.16 })));
    });

    /* hexagonal outer boundary ring */
    const hexMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.30, side: THREE.DoubleSide });
    const hexRing = new THREE.Mesh(new THREE.RingGeometry(1.72, 1.76, 6), hexMat);
    actDeliver.add(hexRing);

    /* secondary orbit ring */
    const deliverRing2Mat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.18, side: THREE.DoubleSide });
    const deliverRing2 = new THREE.Mesh(new THREE.RingGeometry(1.92, 1.95, 72), deliverRing2Mat);
    deliverRing2.rotation.x = Math.PI / 5;
    actDeliver.add(deliverRing2);

    actDeliver.position.z = -18;
    scene.add(actDeliver);

    /* ════════════════════════════════════════════════════════════════════════
       ACT 1 — Skills Constellation  (z = -27)
       ════════════════════════════════════════════════════════════════════════ */
    const act1 = new THREE.Group();
    const skillColors = [T, G, T, V, T, G, W];
    const skillNodes  = [];

    skillColors.forEach((col, i) => {
      const frac  = i / 7;
      const angle = frac * Math.PI * 2;
      const x = Math.cos(angle) * 1.85;
      const y = (frac - 0.5) * 2.8;
      const z = Math.sin(angle) * 0.55;

      const node = new THREE.Mesh(
        new THREE.SphereGeometry(0.09, 14, 14),
        new THREE.MeshBasicMaterial({ color: col })
      );
      node.position.set(x, y, z);
      skillNodes.push(node);
      act1.add(node);

      const rng = new THREE.Mesh(
        new THREE.RingGeometry(0.14, 0.19, 40),
        new THREE.MeshBasicMaterial({ color: col, transparent: true, opacity: 0.28, side: THREE.DoubleSide })
      );
      rng.position.set(x, y, z);
      act1.add(rng);
    });

    for (let i = 0; i < skillNodes.length; i++) {
      for (let j = i + 1; j < skillNodes.length; j++) {
        const a = skillNodes[i].position, b = skillNodes[j].position;
        const geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(
          new Float32Array([a.x, a.y, a.z, b.x, b.y, b.z]), 3
        ));
        act1.add(new THREE.Line(geo, new THREE.LineBasicMaterial({
          color: T, transparent: true, opacity: 0.10
        })));
      }
    }

    act1.position.z = -27;
    scene.add(act1);

    /* ════════════════════════════════════════════════════════════════════════
       ACT 2 — Security  (z = -37)
       ════════════════════════════════════════════════════════════════════════ */
    const act2  = new THREE.Group();
    const octs  = [];
    [[0.38, T, 0.50], [0.76, G, 0.40], [1.18, T, 0.28]].forEach(([sz, col, op]) => {
      const m = new THREE.Mesh(
        new THREE.OctahedronGeometry(sz, 0),
        new THREE.MeshBasicMaterial({ color: col, wireframe: true, transparent: true, opacity: op })
      );
      octs.push(m);
      act2.add(m);
    });

    const scan1Mat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.55, side: THREE.DoubleSide });
    const scan1 = new THREE.Mesh(new THREE.RingGeometry(1.32, 1.36, 72), scan1Mat);
    act2.add(scan1);

    const scan2Mat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.32, side: THREE.DoubleSide });
    const scan2 = new THREE.Mesh(new THREE.RingGeometry(1.52, 1.55, 72), scan2Mat);
    scan2.rotation.x = Math.PI / 4;
    act2.add(scan2);

    const dot1 = new THREE.Mesh(new THREE.SphereGeometry(0.055, 8, 8), new THREE.MeshBasicMaterial({ color: T }));
    const dot2 = new THREE.Mesh(new THREE.SphereGeometry(0.045, 8, 8), new THREE.MeshBasicMaterial({ color: G }));
    act2.add(dot1, dot2);

    act2.position.z = -37;
    scene.add(act2);

    /* ════════════════════════════════════════════════════════════════════════
       ACT 3 — CTA Convergence  (z = -47)
       ════════════════════════════════════════════════════════════════════════ */
    const act3 = new THREE.Group();

    const coreMat  = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.92 });
    const coreGlow = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.14 });
    act3.add(new THREE.Mesh(new THREE.SphereGeometry(0.24, 20, 20), coreMat));
    act3.add(new THREE.Mesh(new THREE.SphereGeometry(0.44, 20, 20), coreGlow));

    const rayDir = () => new THREE.Vector3(
      (Math.random() - 0.5), (Math.random() - 0.5), (Math.random() - 0.5)
    ).normalize().multiplyScalar(2.8);

    for (let i = 0; i < 24; i++) {
      const d = rayDir();
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(
        new Float32Array([0,0,0, d.x, d.y, d.z]), 3
      ));
      act3.add(new THREE.Line(geo, new THREE.LineBasicMaterial({
        color: i % 5 === 0 ? G : T, transparent: true, opacity: 0.16
      })));
    }

    const ctaRingMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.48, side: THREE.DoubleSide });
    const ctaRing = new THREE.Mesh(new THREE.RingGeometry(0.58, 0.62, 72), ctaRingMat);
    act3.add(ctaRing);

    act3.position.z = -47;
    scene.add(act3);

    /* ════════════════════════════════════════════════════════════════════════
       ACT C — Connect / Footer  (z = -56)
       Compass portal: 4-spoke star + pulsing rings + cardinal nodes
       ════════════════════════════════════════════════════════════════════════ */
    const actConnect = new THREE.Group();

    const connectCoreMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.90 });
    const connectCore    = new THREE.Mesh(new THREE.SphereGeometry(0.22, 20, 20), connectCoreMat);
    actConnect.add(connectCore);

    const connectGlowMat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.18 });
    actConnect.add(new THREE.Mesh(new THREE.SphereGeometry(0.46, 20, 20), connectGlowMat));

    const compassRingMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.62, side: THREE.DoubleSide });
    const compassRing    = new THREE.Mesh(new THREE.RingGeometry(0.72, 0.76, 72), compassRingMat);
    actConnect.add(compassRing);

    const compassRing2Mat = new THREE.MeshBasicMaterial({ color: T, transparent: true, opacity: 0.28, side: THREE.DoubleSide });
    const compassRing2    = new THREE.Mesh(new THREE.RingGeometry(1.10, 1.13, 72), compassRing2Mat);
    compassRing2.rotation.x = Math.PI / 4;
    actConnect.add(compassRing2);

    /* 4 cardinal spokes + tip nodes */
    const cardinalDirs = [[1,0],[0,1],[-1,0],[0,-1]];
    cardinalDirs.forEach(([dx, dy], i) => {
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(
        new Float32Array([0,0,0, dx*1.52, dy*1.52, 0]), 3
      ));
      actConnect.add(new THREE.Line(geo, new THREE.LineBasicMaterial({
        color: i % 2 === 0 ? G : T, transparent: true, opacity: 0.42
      })));
      const node = new THREE.Mesh(
        new THREE.SphereGeometry(0.068, 10, 10),
        new THREE.MeshBasicMaterial({ color: i % 2 === 0 ? G : T })
      );
      node.position.set(dx * 1.52, dy * 1.52, 0);
      actConnect.add(node);
    });

    const connectOuterMat = new THREE.MeshBasicMaterial({ color: G, transparent: true, opacity: 0.20, side: THREE.DoubleSide });
    const connectOuter    = new THREE.Mesh(new THREE.RingGeometry(1.56, 1.60, 72), connectOuterMat);
    actConnect.add(connectOuter);

    actConnect.position.z = -56;
    scene.add(actConnect);

    /* ════════════════════════════════════════════════════════════════════════
       WORMHOLE TUNNEL  —  minimal neumorphic
       Three-tier ring system on a warm brown-orange palette.
         Tier 1 ghost rings  — near-void, 5% opacity  →  depth suggestion
         Tier 2 shadow rings — muted brown, 9% opacity →  soft shadow layer
         Tier 3 glow rings   — cream amber, 26% opacity →  neumorphic highlight
       Very few struts, sparse vertex dots, barely-there inner haze.
       ════════════════════════════════════════════════════════════════════════ */
    const mobile  = window.innerWidth < 768;
    const RINGS   = mobile ?  9 : 15;
    const VPR     = mobile ? 14 : 22;
    const TZ_NEAR =  3;
    const TZ_FAR  = -63;
    const TZ_STEP = (TZ_FAR - TZ_NEAR) / (RINGS - 1);
    const BASE_R  = 3.6;

    /* warm brown-orange palette — each step moves toward cream highlight */
    const cVoid   = new THREE.Color(0x0C0604);   /* near-void ghost */
    const cShadow = new THREE.Color(0x241208);   /* dark warm shadow */
    const cMuted  = new THREE.Color(0x44200E);   /* muted brown */
    const cWarm   = new THREE.Color(0x7A4020);   /* warm strut colour */
    const cSoft   = new THREE.Color(0xA86038);   /* soft orange-brown */
    const cGlow   = new THREE.Color(0xC89050);   /* cream glow — accent rings */

    /* precompute ring XY vertex positions */
    const ringVerts = [];
    for (let r = 0; r < RINGS; r++) {
      const z      = TZ_NEAR + r * TZ_STEP;
      const rMult  = 0.92 + Math.sin(z * 0.30 + 0.9) * 0.08;
      const radius = BASE_R * rMult;
      const verts  = [];
      for (let v = 0; v < VPR; v++) {
        const a = (v / VPR) * Math.PI * 2 + r * 0.05;   /* minimal helical twist */
        verts.push({ x: Math.cos(a) * radius, y: Math.sin(a) * radius * 0.62 });
      }
      ringVerts.push(verts);
    }

    /* ── three-tier rings ── */
    const tunnelRings = [];
    for (let r = 0; r < RINGS; r++) {
      const z     = TZ_NEAR + r * TZ_STEP;
      const verts = ringVerts[r];
      const pts   = new Float32Array(VPR * 3);
      verts.forEach(({ x, y }, i) => { pts[i*3] = x; pts[i*3+1] = y; pts[i*3+2] = 0; });
      const geo = new THREE.BufferGeometry();
      geo.setAttribute('position', new THREE.BufferAttribute(pts, 3));

      const isGlow   = r % 5 === 0;          /* cream highlight */
      const isShadow = !isGlow && r % 3 === 0; /* shadow midtone  */
      const col = isGlow ? cGlow : isShadow ? cSoft : cVoid;
      const op  = isGlow ? 0.26  : isShadow ? 0.09  : 0.05;

      const ring = new THREE.LineLoop(geo,
        new THREE.LineBasicMaterial({ color: col, transparent: true, opacity: op }));
      ring.position.z        = z;
      ring.userData.phaseOff = r * 0.34;
      ring.userData.spinDir  = r % 2 === 0 ? 1 : -1;
      ring.userData.isGlow   = isGlow;
      scene.add(ring);
      tunnelRings.push(ring);
    }

    /* ── minimal struts — no cross-diagonals, just clean parallels ── */
    const STRUTS = mobile ? 5 : 8;
    const sP = [], sC = [];

    for (let s = 0; s < STRUTS; s++) {
      const vIdx  = Math.round((s / STRUTS) * VPR) % VPR;
      const prime = s % 2 === 0;
      const col   = prime ? cWarm : cMuted;
      const alpha = prime ? 0.11 : 0.04;

      for (let r = 0; r < RINGS - 1; r++) {
        const z1 = TZ_NEAR +  r      * TZ_STEP;
        const z2 = TZ_NEAR + (r + 1) * TZ_STEP;
        const v1 = ringVerts[r][vIdx];
        const v2 = ringVerts[r + 1][vIdx];
        sP.push(v1.x, v1.y, z1,  v2.x, v2.y, z2);
        sC.push(col.r*alpha, col.g*alpha, col.b*alpha,
                col.r*alpha, col.g*alpha, col.b*alpha);
      }
    }

    const sGeo = new THREE.BufferGeometry();
    sGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(sP), 3));
    sGeo.setAttribute('color',    new THREE.BufferAttribute(new Float32Array(sC), 3));
    scene.add(new THREE.LineSegments(sGeo,
      new THREE.LineBasicMaterial({ vertexColors: true, transparent: true, opacity: 1 })));

    /* ── vertex dots — only on glow + shadow rings, very sparse ── */
    const vP = [], vC = [];
    for (let r = 0; r < RINGS; r++) {
      const z      = TZ_NEAR + r * TZ_STEP;
      const isGlow = r % 5 === 0;
      const isMid  = !isGlow && r % 3 === 0;
      if (!isGlow && !isMid) continue;      /* ghost rings: no dots */

      const stride = isGlow ? 3 : 5;
      ringVerts[r].forEach(({ x, y }, v) => {
        if (v % stride === 0) {
          vP.push(x, y, z);
          const col = isGlow ? cGlow : cSoft;
          const k   = isGlow
            ? (0.32 + Math.random() * 0.28)
            : (0.10 + Math.random() * 0.12);
          vC.push(col.r*k, col.g*k, col.b*k);
        }
      });
    }

    /* inner bore — near-invisible dust */
    const HAZE = mobile ? 18 : 40;
    for (let i = 0; i < HAZE; i++) {
      const z  = TZ_NEAR + Math.random() * (TZ_FAR - TZ_NEAR);
      const a  = Math.random() * Math.PI * 2;
      const ri = Math.random() * 1.9;
      vP.push(Math.cos(a) * ri, Math.sin(a) * ri * 0.62, z);
      const k = 0.02 + Math.random() * 0.03;
      vC.push(cWarm.r*k, cWarm.g*k, cWarm.b*k);
    }

    const vtxGeo = new THREE.BufferGeometry();
    vtxGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(vP), 3));
    vtxGeo.setAttribute('color',    new THREE.BufferAttribute(new Float32Array(vC), 3));
    scene.add(new THREE.Points(vtxGeo,
      new THREE.PointsMaterial({ size: 0.046, vertexColors: true, transparent: true, opacity: 0.82, sizeAttenuation: true })));

    /* ════════════════════════════════════════════════════════════════════════
       PANEL VISIBILITY  —  6 acts over progress [0,1]
       Camera Z center of each act → progress value used to set fade windows.
       ════════════════════════════════════════════════════════════════════════ */
    const PANELS = [
      { id: 'panel-intro',             fadeIn: -0.10, full: 0.000, fadeOut: 0.110, gone: 0.160 },
      { id: 'panel-about-milestone',   fadeIn:  0.16, full: 0.220, fadeOut: 0.310, gone: 0.360 },
      { id: 'panel-deliver-milestone', fadeIn:  0.34, full: 0.400, fadeOut: 0.490, gone: 0.540 },
      { id: 'panel-skills',            fadeIn:  0.49, full: 0.560, fadeOut: 0.650, gone: 0.700 },
      { id: 'panel-security',          fadeIn:  0.68, full: 0.750, fadeOut: 0.830, gone: 0.880 },
      { id: 'panel-cta',               fadeIn:  0.86, full: 0.910, fadeOut: 0.945, gone: 0.965 },
      { id: 'panel-footer-milestone',  fadeIn:  0.95, full: 0.975, fadeOut: 0.991, gone: 1.000 },
    ];

    const scrollHint = document.getElementById('scroll-hint');

    /* cache panel elements + their .panel-content child for depth transform */
    const panelData = PANELS.map(p => ({
      ...p,
      el:      document.getElementById(p.id),
      content: document.querySelector(`#${p.id} .panel-content`),
    }));

    function updatePanels(prog) {
      panelData.forEach(({ el, content, fadeIn, full, fadeOut, gone }) => {
        if (!el) return;
        let op = 0;
        if      (prog < fadeIn)  op = 0;
        else if (prog < full)    op = smoothstep(fadeIn, full, prog);
        else if (prog < fadeOut) op = 1;
        else if (prog < gone)    op = 1 - smoothstep(fadeOut, gone, prog);
        el.style.opacity       = op.toFixed(3);
        el.style.pointerEvents = op > 0.05 ? 'auto' : 'none';

        /* depth fly-in: content arrives from deep Z toward the viewer.
           inProg races ahead of opacity so the card "snaps" into place
           before the full fade completes — reinforcing wormhole depth. */
        if (content) {
          const inProg = smoothstep(0, 1, Math.min(op * 2.2, 1));
          const zDepth = lerp(-1100, 0, inProg);
          const sc     = lerp(0.04, 1,  inProg);
          content.style.transform =
            `perspective(1100px) translateZ(${zDepth.toFixed(0)}px) scale(${sc.toFixed(4)})`;
        }
      });
      if (scrollHint) scrollHint.style.opacity = prog < 0.03 ? '1' : '0';
    }

    /* ════════════════════════════════════════════════════════════════════════
       SCROLL + MOUSE STATE
       ════════════════════════════════════════════════════════════════════════ */
    let scrollProg  = 0;
    let camZTarget  = 3;
    let camZCurrent = 3;
    let mx = 0, my = 0;

    function readScroll() {
      const corridor   = document.getElementById('corridor-hero');
      if (!corridor) return;
      const scrollable = corridor.offsetHeight - window.innerHeight;
      if (scrollable <= 0) return;

      scrollProg = Math.max(0, Math.min(window.scrollY / scrollable, 1));
      camZTarget = 3 - scrollProg * 62;   /* +3 → -59 */

      const pastCorridor = window.scrollY - corridor.offsetHeight;
      viewport.style.opacity = pastCorridor > 0
        ? Math.max(0, 1 - pastCorridor / window.innerHeight).toFixed(3)
        : '1';
    }

    window.addEventListener('scroll',    readScroll,           { passive: true });
    window.addEventListener('mousemove', e => {
      mx = (e.clientX / window.innerWidth  - 0.5) * 2;
      my = (e.clientY / window.innerHeight - 0.5) * 2;
    }, { passive: true });

    /* ════════════════════════════════════════════════════════════════════════
       ANIMATION LOOP
       ════════════════════════════════════════════════════════════════════════ */
    let loadT = 0;
    const LOAD_DUR = 1.8;
    const clock    = new THREE.Clock();

    (function loop() {
      requestAnimationFrame(loop);
      const t  = clock.getElapsedTime();

      loadT = Math.min(t / LOAD_DUR, 1);
      const ls = smoothstep(0, 1, loadT);

      /* ── Act 0 ── */
      ico.rotation.y = t * 0.22;
      ico.rotation.x = t * 0.11;
      ico.scale.setScalar(lerp(0.05, 1, ls));

      const b0 = 0.92 + Math.sin(t * 1.35) * 0.08;
      ring0.scale.set(lerp(2, b0, ls), lerp(2, b0, ls), 1);
      ring0.rotation.z   = t * 0.16;
      ring0Mat.opacity   = (0.28 + Math.sin(t * 1.35) * 0.20) * ls;
      ring0b.rotation.x  = Math.PI / 3 + t * 0.08;
      ring0b.rotation.z  = t * 0.11;
      ring0bMat.opacity  = (0.12 + Math.sin(t * 1.7 + 0.9) * 0.10) * ls;

      /* ── Act A — About Me ── */
      actAbout.rotation.y = t * 0.08;
      aboutTorus.rotation.z  = t * 0.20;
      aboutTorus.rotation.x  = Math.sin(t * 0.28) * 0.18;
      aboutTorusMat.opacity  = 0.52 + Math.sin(t * 1.2) * 0.20;
      aboutTorus2.rotation.x = Math.PI / 3 + t * 0.10;
      aboutTorus2.rotation.y = -t * 0.07;
      aboutOuterRing.rotation.z = -t * 0.08;
      aboutRingMat.opacity   = 0.12 + Math.sin(t * 0.9 + 1.0) * 0.08;
      const bAbout = 0.88 + Math.sin(t * 1.4) * 0.12;
      aboutCore.scale.setScalar(bAbout);
      aboutCoreMat.opacity   = 0.72 + Math.sin(t * 1.1) * 0.16;

      /* orbiters revolve around the torus plane */
      aboutOrbiters.forEach(({ mesh, ring, idx }) => {
        const a = (idx / 5) * Math.PI * 2 + t * 0.38;
        const px = Math.cos(a) * 0.88;
        const py = Math.sin(a) * 0.56;
        const pz = Math.sin(a * 1.4) * 0.28;
        mesh.position.set(px, py, pz);
        ring.position.set(px, py, pz);
        const pulse = 0.82 + Math.sin(t * 1.5 + idx * 1.2) * 0.18;
        mesh.scale.setScalar(pulse);
      });

      /* ── Act B — What I Bring ── */
      actDeliver.rotation.y = t * 0.06;
      deliverHub.rotation.y  = t * 0.28;
      deliverHub.rotation.x  = t * 0.14;
      deliverHubSolid.rotation.y = -t * 0.18;
      hexRing.rotation.z     = t * 0.05;
      hexRing.rotation.x     = Math.sin(t * 0.22) * 0.14;
      hexMat.opacity         = 0.22 + Math.sin(t * 0.8) * 0.10;
      deliverRing2.rotation.x = Math.PI / 5 + t * 0.09;
      deliverRing2.rotation.z = t * 0.13;

      deliverNodes.forEach((node, i) => {
        const pulse = 0.78 + Math.sin(t * 1.3 + i * (Math.PI * 2 / 7)) * 0.22;
        node.scale.setScalar(pulse);
      });

      /* ── Act 1 — Skills Constellation ── */
      act1.rotation.y = t * 0.07;
      skillNodes.forEach((node, i) => {
        const pulse = 0.88 + Math.sin(t * 1.1 + i * 0.9) * 0.12;
        node.scale.setScalar(pulse);
      });

      /* ── Act 2 — Security ── */
      octs[0].rotation.y = t * 0.32; octs[0].rotation.x = t * 0.16;
      octs[1].rotation.y = -t * 0.19; octs[1].rotation.z = t * 0.08;
      octs[2].rotation.x = t * 0.11; octs[2].rotation.z = -t * 0.06;
      scan1.rotation.z   = t * 0.42;
      scan1Mat.opacity   = 0.38 + Math.sin(t * 2.0) * 0.22;
      scan2.rotation.x   = Math.PI / 4 + t * 0.14;
      scan2.rotation.z   = t * 0.24;
      scan2Mat.opacity   = 0.18 + Math.sin(t * 1.5 + 1.0) * 0.14;
      dot1.position.set(Math.cos(t * 0.85) * 1.34, Math.sin(t * 0.85) * 1.34, 0);
      const r2 = 1.54;
      dot2.position.set(
        Math.cos(-t * 0.62) * r2,
        Math.sin(-t * 0.62) * r2 * Math.cos(Math.PI / 4),
        Math.sin(-t * 0.62) * r2 * Math.sin(Math.PI / 4)
      );

      /* ── Act 3 — CTA ── */
      const b3 = 0.88 + Math.sin(t * 1.45) * 0.12;
      act3.children[0].scale.setScalar(b3);
      act3.children[1].scale.setScalar(0.84 + Math.sin(t * 1.45 + 0.5) * 0.16);
      ctaRing.rotation.z    = t * 0.24;
      ctaRingMat.opacity    = 0.32 + Math.sin(t * 1.4) * 0.16;
      act3.rotation.y       = t * 0.06;

      /* ── Act C — Connect ── */
      actConnect.rotation.y = t * 0.10;
      connectCore.scale.setScalar(0.88 + Math.sin(t * 1.35) * 0.12);
      connectCoreMat.opacity  = 0.72 + Math.sin(t * 1.1) * 0.18;
      compassRing.rotation.z  = t * 0.18;
      compassRingMat.opacity  = 0.42 + Math.sin(t * 1.2) * 0.20;
      compassRing2.rotation.x = Math.PI / 4 + t * 0.09;
      compassRing2.rotation.z = -t * 0.12;
      compassRing2Mat.opacity = 0.18 + Math.sin(t * 0.95 + 1.2) * 0.10;
      connectOuter.rotation.z = -t * 0.07;
      connectOuterMat.opacity = 0.12 + Math.sin(t * 0.80 + 0.8) * 0.08;

      /* ── Tunnel — micro-breathing, barely-there rotation ── */
      tunnelRings.forEach(ring => {
        /* glow rings pulse slightly more than ghost rings */
        const amp   = ring.userData.isGlow ? 0.016 : 0.008;
        const pulse = 1.0 + Math.sin(t * 0.28 + ring.userData.phaseOff) * amp;
        ring.scale.set(pulse, pulse, 1);
        ring.rotation.z = t * 0.003 * ring.userData.spinDir;
      });

      /* ── Camera ── */
      camZCurrent += (camZTarget - camZCurrent) * 0.055;
      camera.position.x += (mx * 0.38 - camera.position.x) * 0.018;
      camera.position.y += (-my * 0.24 - camera.position.y) * 0.018;
      camera.position.z  = camZCurrent;
      camera.lookAt(
        camera.position.x * 0.35,
        camera.position.y * 0.35,
        camZCurrent - 6
      );

      updatePanels(scrollProg);
      renderer.render(scene, camera);
    })();
  }
})();
