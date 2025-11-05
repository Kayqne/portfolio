// Drag & drop reorder in admin/secao.php
document.addEventListener('DOMContentLoaded', () => {
  const mosaic = document.getElementById('mosaic');
  if (mosaic) {
    let dragEl = null;
    mosaic.addEventListener('dragstart', e => {
      const t = e.target.closest('.tile-draggable');
      if (!t) return;
      dragEl = t;
      t.classList.add('dragging');
    });
    mosaic.addEventListener('dragend', e => {
      const t = e.target.closest('.tile-draggable');
      if (!t) return;
      t.classList.remove('dragging');
      saveOrder();
    });
    mosaic.addEventListener('dragover', e => {
      e.preventDefault();
      const after = getDragAfterElement(mosaic, e.clientX, e.clientY);
      const cur = document.querySelector('.dragging');
      if (!cur) return;
      if (after == null) { mosaic.appendChild(cur); } else { mosaic.insertBefore(cur, after); }
    });
    function getDragAfterElement(container, x, y){
      const els = [...container.querySelectorAll('.tile-draggable:not(.dragging)')];
      return els.reduce((closest,child)=>{
        const rect = child.getBoundingClientRect();
        const offset = Math.hypot(x-rect.left, y-rect.top);
        if (offset < closest.offset) { return {offset:offset, element:child}; }
        else { return closest; }
      }, {offset: Number.POSITIVE_INFINITY}).element;
    }
    function saveOrder(){
      const ids = [...mosaic.querySelectorAll('.tile-draggable')].map(el=>el.dataset.id).join(',');
      const form = document.getElementById('reorderForm');
      if (!form) return;
      form.querySelector('#orderInput').value = ids;
      const fd = new FormData(form);
      fetch(location.href, {method:'POST', body:fd}).then(r=>r.text()).then(()=>console.log('Reordenado'));
    }
  }

  // Lightbox simples no público
  document.querySelectorAll('.lightbox').forEach(a=>{
    a.addEventListener('click', e => {
      if (a.dataset.type === 'image') {
        e.preventDefault();
        openLightbox(a.href, a.title || '');
      }
    });
  });
});

function openLightbox(src, caption){
  const wrap = document.createElement('div');
  wrap.style.position = 'fixed';
  wrap.style.inset = '0';
  wrap.style.background = 'rgba(0,0,0,.9)';
  wrap.style.display = 'grid';
  wrap.style.placeItems = 'center';
  wrap.style.zIndex = '999';
  wrap.addEventListener('click', ()=>wrap.remove());
  const img = document.createElement('img');
  img.src = src;
  img.alt = caption;
  img.style.maxWidth = '90vw';
  img.style.maxHeight = '85vh';
  img.style.objectFit = 'contain';
  const cap = document.createElement('div');
  cap.textContent = caption;
  cap.style.marginTop = '8px';
  cap.style.color = '#daa520';
  cap.style.textAlign = 'center';
  const box = document.createElement('div');
  box.style.textAlign = 'center';
  box.appendChild(img);
  box.appendChild(cap);
  wrap.appendChild(box);
  document.body.appendChild(wrap);
}
