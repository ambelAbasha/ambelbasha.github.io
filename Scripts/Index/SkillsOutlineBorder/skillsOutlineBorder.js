(function() {
  function attachOutlines() {
    // Remove existing outlines (if re-running)
    document.querySelectorAll('.skill-box .outline-svg').forEach(n => n.remove());

    document.querySelectorAll('.skills .skill-box').forEach(box => {
      const wrapper = document.createElement('div');
      wrapper.className = 'outline-svg';

      const svgNS = "http://www.w3.org/2000/svg";
      const svg = document.createElementNS(svgNS, 'svg');
      const rect = document.createElementNS(svgNS, 'rect');

      wrapper.appendChild(svg);
      svg.appendChild(rect);
      box.appendChild(wrapper);

      const strokeW = 3; // px stroke width
      const halfStroke = strokeW / 2;

      // Function to size svg and rect to actual pixel dims
      function updateSizing() {
        // use offsetWidth/Height which include padding but not transform
        const w = Math.max(1, box.clientWidth);
        const h = Math.max(1, box.clientHeight);

        // set viewBox to real pixels so rect coordinates match pixels
        svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
        svg.setAttribute('width', w);
        svg.setAttribute('height', h);

        // position rect inset by half stroke so stroke stays fully inside
        rect.setAttribute('x', halfStroke);
        rect.setAttribute('y', halfStroke);
        rect.setAttribute('width', Math.max(0, w - strokeW));
        rect.setAttribute('height', Math.max(0, h - strokeW));

        // border-radius: compute from computed style (simple one-value handle)
        const cs = getComputedStyle(box);
        const br = cs.borderRadius || '0px';
        let radiusPx = 0;
        try {
          if (br.endsWith('px')) radiusPx = parseFloat(br);
          else if (br.endsWith('%')) radiusPx = (parseFloat(br) / 100) * w;
          else radiusPx = parseFloat(br) || 0;
        } catch(e) { radiusPx = 0; }
        // ensure rx doesn't exceed half of smaller side
        radiusPx = Math.min(radiusPx, Math.min((w - strokeW) / 2, (h - strokeW) / 2));
        rect.setAttribute('rx', radiusPx);
        rect.setAttribute('ry', radiusPx);

        // compute perimeter of rect (approx for rounded rect — good enough)
        const rectW = Math.max(0, w - strokeW);
        const rectH = Math.max(0, h - strokeW);
        const perimeter = 2 * (rectW + rectH);

        // apply dasharray and offset
        rect.style.strokeDasharray = perimeter;
        rect.style.strokeDashoffset = perimeter;

        // ensure transition is off initially
        rect.style.transition = 'none';
      }

      // draw animation: dashoffset from perimeter -> 0
      function startDraw() {
        // ensure sizing current
        updateSizing();

        // quick reset without transition
        const per = parseFloat(rect.style.strokeDasharray) || 0;
        rect.style.transition = 'none';
        rect.style.strokeDashoffset = per;

        // force reflow so transition will apply
        rect.getBoundingClientRect();

        // then animate to 0
        rect.style.transition = 'stroke-dashoffset 1.0s linear';
        rect.style.strokeDashoffset = '0';

        // optional: leave it visible. If you want it to disappear after a delay,
        // uncomment the block below.
        /*
        clearTimeout(box._outlineTimeout);
        box._outlineTimeout = setTimeout(() => {
          rect.style.transition = 'stroke-dashoffset 0.6s ease-in';
          rect.style.strokeDashoffset = rect.style.strokeDasharray;
        }, 1400);
        */
      }

      // hover/focus listeners
      box.addEventListener('mouseenter', startDraw);
      box.addEventListener('focus', startDraw, true);

      // Observe size changes and update viewBox/perimeter
      const ro = new ResizeObserver(() => {
        // small debounce via rAF
        window.requestAnimationFrame(() => updateSizing());
      });
      ro.observe(box);
      box._outlineResizeObserver = ro;

      // initial sizing
      updateSizing();
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachOutlines);
  } else {
    attachOutlines();
  }

  // expose for debugging/re-run after dynamic changes
  window.__attachSkillOutlines = attachOutlines;
})();
