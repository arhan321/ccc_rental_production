/* ---------- batas paket: 2 × 3 hari = 6 hari ---------- */
const MAX_PAKET = 2;

document.addEventListener('DOMContentLoaded', () => {

  /* ──────────────────────
     0. Elemen & helper
  ────────────────────── */
  const cartBody           = document.querySelector('.offcanvas-body');
  const cartItemsContainer = Object.assign(document.createElement('div'),
    { id:'dynamicCartItems', className:'mb-4' });
  cartBody.insertBefore(cartItemsContainer,
    document.getElementById('cartItems').nextSibling);

  const subtotalEl = document.getElementById('subtotal');
  const totalEl    = document.getElementById('total');
  const cartBadge  = document.getElementById('cartBadge');

  const toISO    = d=>d.toISOString().split('T')[0];
  const todayISO = ()=>toISO(new Date());
  const addDays  = (d,n)=>new Date(d.getTime()+n*864e5);

  /* ===============================================================
       1. Tombol “Sewa Sekarang”
  ============================================================== */
  document.querySelectorAll('.btn.btn-outline-dark.btn-sm:not([disabled])')
    .forEach(btn=>btn.addEventListener('click', ()=>{

      const card = btn.closest('.card');
      const col  = card.closest('[data-kostums-id]');
      const id   = col.dataset.kostumsId;
      if(cartItemsContainer.querySelector(`[data-kostums-id="${id}"]`)) return;

      const title = card.querySelector('.card-title').textContent;
      const cat   = col.dataset.category;
      const size  = col.dataset.size || 'L';
      const base  = +col.dataset.price;
      const img   = card.querySelector('img').src;

      let paket = 1;                       // 1 paket = 3-hari

      const item = document.createElement('div');
      item.className = 'cart-item d-flex gap-3 rounded shadow-sm p-3 mb-3';
      item.dataset.kostumsId = id;
      item.dataset.size      = size;
      item.dataset.basePrice = base;
      item.innerHTML = `
        <img src="${img}" class="rounded flex-shrink-0"
             style="width:60px;height:60px;object-fit:cover;">
        <div class="flex-grow-1 d-flex justify-content-between flex-wrap">
          <div class="pe-3" style="max-width:calc(100% - 110px);">
            <h6 class="mb-1 fw-semibold" style="font-size:.85rem;">${title}</h6>
            <span class="badge category-badge mb-1">${cat}</span>
            <div class="text-muted" style="font-size:.75rem;">
              <div>Ukuran: <strong>${size}</strong></div>
              <div class="mb-1">
                <label class="form-label mb-0" style="font-size:.75rem;">Tanggal Mulai</label>
                <input type="date" class="form-control form-control-sm rental-start"
                       value="${todayISO()}">
              </div>
              <div class="mb-1">
                <label class="form-label mb-0" style="font-size:.75rem;">Tanggal Selesai</label>
                <input type="date" class="form-control form-control-sm rental-end" readonly>
              </div>
              <div class="rental-duration">Durasi: 3 hari</div>
            </div>
          </div>

          <div class="d-flex flex-column align-items-end justify-content-between"
               style="min-width:100px;">
            <div class="paket-wrapper d-flex align-items-center gap-2 mb-2">
              <button class="paket-minus rounded px-2" style="font-size:.8rem;">–</button>
              <span   class="paket-value" style="font-size:.8rem;">1</span>
              <button class="paket-plus  rounded px-2" style="font-size:.8rem;">+</button>
            </div>
            <div class="price fw-semibold text-dark" style="font-size:.875rem;">
              Rp${base.toLocaleString()}
            </div>
          </div>
        </div>`;
      cartItemsContainer.appendChild(item);

      const paketVal   = item.querySelector('.paket-value');
      const priceEl    = item.querySelector('.price');
      const startInp   = item.querySelector('.rental-start');
      const endInp     = item.querySelector('.rental-end');
      const durText    = item.querySelector('.rental-duration');
      const paketMinus = item.querySelector('.paket-minus');
      const paketPlus  = item.querySelector('.paket-plus');

      const recalc = ()=>{
        const hari = paket*3;
        paketVal.textContent = paket;
        priceEl.textContent  = `Rp${(base*paket).toLocaleString()}`;
        durText.textContent  = `Durasi: ${hari} hari`;
        endInp.value         = toISO(addDays(new Date(startInp.value), hari-1));
        paketPlus.disabled   = paket>=MAX_PAKET;
        paketMinus.disabled  = paket<=1;
        updateTotals();
      };
      paketMinus.onclick = ()=>{ if(paket>1){paket--;recalc();} };
      paketPlus .onclick = ()=>{ if(paket<MAX_PAKET){paket++;recalc();} };
      startInp.onchange  = recalc;
      recalc();
  }));

  /* ---------- subtotal ---------- */
  function updateTotals(){
    let total = 0;
    cartItemsContainer.querySelectorAll('.cart-item').forEach(ci=>{
      const paket = +ci.querySelector('.paket-value').textContent;
      total += paket * +ci.dataset.basePrice;
    });
    subtotalEl.textContent = totalEl.textContent = `Rp${total.toLocaleString()}`;
    const n = cartItemsContainer.children.length;
    cartBadge.textContent = n;
    cartBadge.style.display = n? 'inline-block':'none';
  }

  /* ===============================================================
       2. Checkout  +  Snap
  ============================================================== */
  document.getElementById('checkoutBtn')?.addEventListener('click', ()=>{

    const items = [...cartItemsContainer.querySelectorAll('.cart-item')].map(ci=>{
      const paket = +ci.querySelector('.paket-value').textContent;
      return {
        kostums_id : ci.dataset.kostumsId,
        ukuran     : ci.dataset.size,
        price      : +ci.dataset.basePrice,
        durasi     : paket*3,
      };
    });
    if(!items.length) return alert('Keranjang kosong.');

    const total        = +totalEl.textContent.replace(/[^\d]/g,'');
    const tanggalMulai = cartItemsContainer.querySelector('.rental-start')?.value;
    const totalDurasi  = items.reduce((s,i)=>s+i.durasi,0);

    fetch('/checkout',{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,
      },
      body:JSON.stringify({
        tanggal_mulai:tanggalMulai,
        durasi:totalDurasi,
        total:total,
        alamat_toko:'CCC Rental - Jakarta',
        items:items
      })
    })
    .then(r=>r.ok?r.json():Promise.reject('Checkout gagal'))
    .then(d=>{
      if(!d.snap_token) return alert('Gagal mendapatkan token');
      handleSnap(d.snap_token, d.order_id_full);
      cartItemsContainer.innerHTML='';
      updateTotals();
    })
    .catch(e=>alert(e));
  });

  /* send status ke backend */
  function postStatus(orderFull, trxStatus){
    return fetch('/checkout/status',{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
      },
      body:JSON.stringify({
        order_id_full:orderFull,
        transaction_status:trxStatus
      })
    });
  }

  /* buka Snap & set callback */
  function handleSnap(token, orderFull){
    window.snap.pay(token,{
      onSuccess : res=>postStatus(orderFull,res.transaction_status).then(()=>location.href='/pesanan'),
      onPending : res=>postStatus(orderFull,res.transaction_status).then(()=>location.href='/pesanan'),
      onError   : res=>postStatus(orderFull,res.transaction_status).then(()=>location.href='/pesanan'),
      onClose   : ()=> postStatus(orderFull,'pending').then(()=>location.href='/pesanan'),
    });
  }

  /* ===============================================================
       3. Urutkan produk: available → booked
  ============================================================== */
  const productList = document.getElementById('productList');
  const reorderProducts = ()=>{
    if(!productList) return;
    [...productList.children]
      .sort((a,b)=>(a.dataset.status==='booked')-(b.dataset.status==='booked'))
      .forEach(c=>productList.appendChild(c));
  };
  reorderProducts();
  new MutationObserver(muts=>{
    if(muts.some(m=>m.attributeName==='data-status')) reorderProducts();
  }).observe(productList||document.body,{subtree:true,attributes:true,attributeFilter:['data-status']});

  /* ===============================================================
       4. Tombol “Bayar Lagi”
  ============================================================== */
  document.querySelectorAll('.pay-again').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.dataset.order;
      fetch(`/pay-again/${id}`, { headers:{'X-Requested-With':'XMLHttpRequest'} })
      .then(r=>r.json())
      .then(d=>{
        if(!d.snap_token) return alert('Gagal mendapatkan token');
        handleSnap(d.snap_token, d.order_id_full);
      })
      .catch(()=>alert('Terjadi kesalahan, coba lagi.'));
    });
  });

});