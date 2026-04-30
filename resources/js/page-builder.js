import grapesjs from 'grapesjs'

/* ──────────────────────────────────────────────────────────────
   Bootstrap
────────────────────────────────────────────────────────────── */
const cfg = window.PB_CONFIG || {}

/* ──────────────────────────────────────────────────────────────
   Init GrapeJS
────────────────────────────────────────────────────────────── */
const editor = grapesjs.init({
    container: '#pb-canvas',
    fromElement: false,
    height: '100%',
    width: 'auto',
    storageManager: false,

    // Canvas uses our real Tailwind/DaisyUI CSS
    canvas: {
        styles: [cfg.cssUrl].filter(Boolean),
        scripts: [],
    },

    // Inject data-theme on the iframe <html> so DaisyUI works
    canvasOffset: { top: 0, right: 0, bottom: 0, left: 0 },

    // Disable default panels — we build our own
    panels: { defaults: [] },

    // Style Manager — detailed property groups
    styleManager: {
        appendTo: '#pb-styles',
        sectors: [
            {
                name: 'Mise en page',
                open: true,
                properties: [
                    { property: 'display', type: 'select', options: [{ value: 'block', name: 'Block' }, { value: 'flex', name: 'Flex' }, { value: 'grid', name: 'Grid' }, { value: 'none', name: 'Masqué' }] },
                    { property: 'flex-direction', type: 'select', options: [{ value: 'row', name: 'Ligne' }, { value: 'column', name: 'Colonne' }] },
                    { property: 'align-items', type: 'select', options: [{ value: 'flex-start', name: 'Début' }, { value: 'center', name: 'Centre' }, { value: 'flex-end', name: 'Fin' }, { value: 'stretch', name: 'Étirer' }] },
                    { property: 'justify-content', type: 'select', options: [{ value: 'flex-start', name: 'Début' }, { value: 'center', name: 'Centre' }, { value: 'flex-end', name: 'Fin' }, { value: 'space-between', name: 'Espace entre' }, { value: 'space-around', name: 'Espace autour' }] },
                    { property: 'gap', type: 'integer', units: ['px', 'rem', '%'] },
                ],
            },
            {
                name: 'Espacement',
                open: false,
                properties: [
                    { property: 'padding', type: 'composite', properties: [{ property: 'padding-top', type: 'integer', units: ['px', 'rem'] }, { property: 'padding-right', type: 'integer', units: ['px', 'rem'] }, { property: 'padding-bottom', type: 'integer', units: ['px', 'rem'] }, { property: 'padding-left', type: 'integer', units: ['px', 'rem'] }] },
                    { property: 'margin', type: 'composite', properties: [{ property: 'margin-top', type: 'integer', units: ['px', 'rem', 'auto'] }, { property: 'margin-right', type: 'integer', units: ['px', 'rem', 'auto'] }, { property: 'margin-bottom', type: 'integer', units: ['px', 'rem', 'auto'] }, { property: 'margin-left', type: 'integer', units: ['px', 'rem', 'auto'] }] },
                ],
            },
            {
                name: 'Taille',
                open: false,
                properties: [
                    { property: 'width', type: 'integer', units: ['px', '%', 'vw', 'rem'] },
                    { property: 'max-width', type: 'integer', units: ['px', '%', 'rem'] },
                    { property: 'min-height', type: 'integer', units: ['px', 'vh', 'rem'] },
                ],
            },
            {
                name: 'Typographie',
                open: false,
                properties: [
                    { property: 'font-size', type: 'integer', units: ['px', 'rem', 'em'] },
                    { property: 'font-weight', type: 'select', options: [{ value: '400', name: 'Normal' }, { value: '600', name: 'Semi-bold' }, { value: '700', name: 'Bold' }, { value: '800', name: 'Extra-bold' }] },
                    { property: 'line-height', type: 'integer', units: ['', 'px', 'rem'] },
                    { property: 'text-align', type: 'radio', options: [{ value: 'left', name: 'G' }, { value: 'center', name: 'C' }, { value: 'right', name: 'D' }] },
                    { property: 'color', type: 'color' },
                ],
            },
            {
                name: 'Arrière-plan',
                open: false,
                properties: [
                    { property: 'background-color', type: 'color' },
                    { property: 'background-image', type: 'file' },
                    { property: 'background-size', type: 'select', options: [{ value: 'cover', name: 'Cover' }, { value: 'contain', name: 'Contain' }, { value: 'auto', name: 'Auto' }] },
                    { property: 'background-position', type: 'select', options: [{ value: 'center', name: 'Centre' }, { value: 'top', name: 'Haut' }, { value: 'bottom', name: 'Bas' }] },
                ],
            },
            {
                name: 'Bordure',
                open: false,
                properties: [
                    { property: 'border-radius', type: 'integer', units: ['px', 'rem', '%'] },
                    { property: 'border-width', type: 'integer', units: ['px'] },
                    { property: 'border-style', type: 'select', options: [{ value: 'none', name: 'Aucune' }, { value: 'solid', name: 'Solide' }, { value: 'dashed', name: 'Tirets' }] },
                    { property: 'border-color', type: 'color' },
                ],
            },
        ],
    },

    // Block manager
    blockManager: { appendTo: '#pb-blocks' },

    // Layers
    layerManager: { appendTo: '#pb-layers' },

    // Selector manager (appended to right panel header area)
    selectorManager: { appendTo: '#pb-selectors' },

    // Trait manager
    traitManager: { appendTo: '#pb-traits' },

    // Device manager
    deviceManager: {
        devices: [
            { name: 'Desktop', width: '' },
            { name: 'Tablet', width: '768px', widthMedia: '768px' },
            { name: 'Mobile', width: '390px', widthMedia: '390px' },
        ],
    },
})

/* ──────────────────────────────────────────────────────────────
   Patch iframe html element for DaisyUI data-theme
────────────────────────────────────────────────────────────── */
editor.on('canvas:frame:load', ({ el }) => {
    el.contentDocument?.documentElement?.setAttribute('data-theme', 'light')
})

/* ──────────────────────────────────────────────────────────────
   Helper to register a block
────────────────────────────────────────────────────────────── */
function block(id, label, category, content, options = {}) {
    editor.Blocks.add(id, {
        label,
        category,
        content: { type: 'text', content },
        ...options,
        media: options.media || svgIcon(options.icon || 'square'),
    })
}

function svgIcon(name) {
    const icons = {
        hero:        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="4" width="20" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>',
        features:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
        stats:       '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 18L9 12L13 16L21 8"/></svg>',
        cta:         '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M12 11v2M8 12h8"/></svg>',
        testimonial: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        faq:         '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/></svg>',
        pricing:     '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
        team:        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        gallery:     '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        text:        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 6h16M4 12h16M4 18h12"/></svg>',
        alert:       '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" x2="12.01" y1="17" y2="17"/></svg>',
        cards:       '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>',
        image_text:  '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="8" height="18" rx="1"/><path d="M14 7h7M14 12h7M14 17h7"/></svg>',
        square:      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>',
    }
    return icons[name] || icons.square
}

/* ──────────────────────────────────────────────────────────────
   DaisyUI Blocks — 17 blocks across 4 categories
────────────────────────────────────────────────────────────── */

// ── HERO ────────────────────────────────────────────────────
editor.Blocks.add('hero-center', {
    label: 'Hero centré',
    category: 'Hero',
    media: svgIcon('hero'),
    content: `<section class="hero min-h-[60vh] bg-base-200">
  <div class="hero-content text-center flex-col max-w-2xl">
    <h1 class="text-5xl font-bold leading-tight">Votre titre accrocheur ici</h1>
    <p class="py-6 text-lg text-base-content/70">Une description concise de votre offre ou proposition de valeur. Expliquez pourquoi vos visiteurs devraient agir maintenant.</p>
    <div class="flex gap-3 justify-center flex-wrap">
      <a href="#" class="btn btn-primary btn-lg">Commencer</a>
      <a href="#" class="btn btn-ghost btn-lg">En savoir plus</a>
    </div>
  </div>
</section>`,
})

editor.Blocks.add('hero-image', {
    label: 'Hero avec image',
    category: 'Hero',
    media: svgIcon('hero'),
    content: `<section class="hero min-h-[70vh] bg-base-200">
  <div class="hero-content flex-col lg:flex-row gap-12 max-w-6xl mx-auto px-6">
    <img src="https://placehold.co/600x400/f3f4f6/94a3b8?text=Image" class="max-w-sm rounded-2xl shadow-2xl w-full" alt="Hero image" />
    <div>
      <h1 class="text-5xl font-bold">Votre titre principal</h1>
      <p class="py-6 text-lg text-base-content/70">Décrivez votre produit ou service en mettant en avant les bénéfices clés pour votre audience cible.</p>
      <div class="flex gap-3 flex-wrap">
        <a href="#" class="btn btn-primary">Démarrer maintenant</a>
        <a href="#" class="btn btn-outline">Voir la démo</a>
      </div>
    </div>
  </div>
</section>`,
})

// ── FEATURES ─────────────────────────────────────────────────
editor.Blocks.add('features-3col', {
    label: 'Features 3 colonnes',
    category: 'Features',
    media: svgIcon('features'),
    content: `<section class="py-20 bg-base-100">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold mb-4">Nos fonctionnalités</h2>
      <p class="text-base-content/60 max-w-xl mx-auto">Tout ce dont vous avez besoin pour réussir votre projet.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="card bg-base-200 shadow-sm"><div class="card-body text-center">
        <div class="text-primary text-5xl mb-4">⚡</div>
        <h3 class="card-title justify-center">Rapide</h3>
        <p class="text-base-content/70">Performances optimisées pour une expérience utilisateur fluide.</p>
      </div></div>
      <div class="card bg-base-200 shadow-sm"><div class="card-body text-center">
        <div class="text-secondary text-5xl mb-4">🔒</div>
        <h3 class="card-title justify-center">Sécurisé</h3>
        <p class="text-base-content/70">Vos données sont protégées avec les dernières technologies.</p>
      </div></div>
      <div class="card bg-base-200 shadow-sm"><div class="card-body text-center">
        <div class="text-accent text-5xl mb-4">🎯</div>
        <h3 class="card-title justify-center">Précis</h3>
        <p class="text-base-content/70">Des outils adaptés à vos besoins spécifiques.</p>
      </div></div>
    </div>
  </div>
</section>`,
})

editor.Blocks.add('features-2col', {
    label: 'Features 2 colonnes avec icônes',
    category: 'Features',
    media: svgIcon('features'),
    content: `<section class="py-20 bg-base-200">
  <div class="max-w-5xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-12">Pourquoi nous choisir ?</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="flex gap-4"><div class="badge badge-primary badge-lg p-3 shrink-0">✓</div><div><h3 class="font-bold text-lg mb-1">Avantage numéro 1</h3><p class="text-base-content/70">Explication détaillée de ce bénéfice pour l'utilisateur.</p></div></div>
      <div class="flex gap-4"><div class="badge badge-primary badge-lg p-3 shrink-0">✓</div><div><h3 class="font-bold text-lg mb-1">Avantage numéro 2</h3><p class="text-base-content/70">Explication détaillée de ce bénéfice pour l'utilisateur.</p></div></div>
      <div class="flex gap-4"><div class="badge badge-secondary badge-lg p-3 shrink-0">✓</div><div><h3 class="font-bold text-lg mb-1">Avantage numéro 3</h3><p class="text-base-content/70">Explication détaillée de ce bénéfice pour l'utilisateur.</p></div></div>
      <div class="flex gap-4"><div class="badge badge-secondary badge-lg p-3 shrink-0">✓</div><div><h3 class="font-bold text-lg mb-1">Avantage numéro 4</h3><p class="text-base-content/70">Explication détaillée de ce bénéfice pour l'utilisateur.</p></div></div>
    </div>
  </div>
</section>`,
})

// ── STATS ─────────────────────────────────────────────────────
editor.Blocks.add('stats', {
    label: 'Statistiques',
    category: 'Stats',
    media: svgIcon('stats'),
    content: `<section class="py-16 bg-primary text-primary-content">
  <div class="max-w-5xl mx-auto px-6">
    <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-primary text-primary-content">
      <div class="stat"><div class="stat-figure text-primary-content/60">📊</div><div class="stat-title text-primary-content/80">Utilisateurs</div><div class="stat-value">31 000</div><div class="stat-desc text-primary-content/60">↗︎ 400 (22%)</div></div>
      <div class="stat"><div class="stat-figure text-primary-content/60">📦</div><div class="stat-title text-primary-content/80">Projets</div><div class="stat-value">4 200</div><div class="stat-desc text-primary-content/60">↗︎ 90 (14%)</div></div>
      <div class="stat"><div class="stat-figure text-primary-content/60">⭐</div><div class="stat-title text-primary-content/80">Satisfaction</div><div class="stat-value">98%</div><div class="stat-desc text-primary-content/60">↗︎ 2%</div></div>
    </div>
  </div>
</section>`,
})

// ── CTA ────────────────────────────────────────────────────────
editor.Blocks.add('cta-simple', {
    label: 'CTA simple',
    category: 'CTA',
    media: svgIcon('cta'),
    content: `<section class="py-20 bg-base-100">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-bold mb-4">Prêt à vous lancer ?</h2>
    <p class="text-base-content/60 text-lg mb-8">Rejoignez des milliers d'utilisateurs qui font confiance à notre solution.</p>
    <div class="flex gap-4 justify-center flex-wrap">
      <a href="#" class="btn btn-primary btn-lg">Essai gratuit 14 jours</a>
      <a href="#" class="btn btn-outline btn-lg">Parler à un expert</a>
    </div>
    <p class="text-sm text-base-content/40 mt-4">Aucune carte bancaire requise.</p>
  </div>
</section>`,
})

editor.Blocks.add('cta-bg', {
    label: 'CTA avec fond coloré',
    category: 'CTA',
    media: svgIcon('cta'),
    content: `<section class="py-20 bg-gradient-to-br from-primary to-secondary text-primary-content">
  <div class="max-w-3xl mx-auto px-6 text-center">
    <h2 class="text-4xl font-bold mb-4">Transformez votre façon de travailler</h2>
    <p class="text-primary-content/80 text-lg mb-8">Notre plateforme s'adapte à vos besoins, quelle que soit la taille de votre équipe.</p>
    <a href="#" class="btn btn-lg bg-white text-primary hover:bg-white/90 border-0 shadow-xl">Commencer gratuitement</a>
  </div>
</section>`,
})

// ── TESTIMONIALS ────────────────────────────────────────────────
editor.Blocks.add('testimonial-single', {
    label: 'Témoignage',
    category: 'Témoignages',
    media: svgIcon('testimonial'),
    content: `<section class="py-20 bg-base-200">
  <div class="max-w-2xl mx-auto px-6 text-center">
    <div class="text-6xl text-primary mb-6">"</div>
    <blockquote class="text-2xl font-medium italic mb-8">"Ce produit a complètement transformé notre façon de gérer nos projets. Je ne pourrais plus m'en passer."</blockquote>
    <div class="flex items-center justify-center gap-4">
      <div class="avatar"><div class="w-14 rounded-full"><img src="https://placehold.co/56x56/f3f4f6/94a3b8?text=JD" alt="Jean Dupont" /></div></div>
      <div class="text-left"><div class="font-bold">Jean Dupont</div><div class="text-sm text-base-content/60">CEO, Entreprise SA</div></div>
    </div>
  </div>
</section>`,
})

editor.Blocks.add('testimonials-grid', {
    label: 'Témoignages grille',
    category: 'Témoignages',
    media: svgIcon('testimonial'),
    content: `<section class="py-20 bg-base-100">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-12">Ce qu'ils disent de nous</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card bg-base-200 shadow-sm"><div class="card-body"><div class="rating rating-sm mb-3"><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /></div><p class="italic text-base-content/80">"Excellent service, je recommande vivement."</p><div class="mt-4 flex items-center gap-3"><div class="avatar placeholder"><div class="bg-primary text-primary-content rounded-full w-10"><span>A</span></div></div><div><div class="font-semibold">Alice M.</div><div class="text-xs text-base-content/50">Designer</div></div></div></div></div>
      <div class="card bg-base-200 shadow-sm"><div class="card-body"><div class="rating rating-sm mb-3"><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /></div><p class="italic text-base-content/80">"Interface intuitive et support réactif."</p><div class="mt-4 flex items-center gap-3"><div class="avatar placeholder"><div class="bg-secondary text-secondary-content rounded-full w-10"><span>B</span></div></div><div><div class="font-semibold">Bob T.</div><div class="text-xs text-base-content/50">Développeur</div></div></div></div></div>
      <div class="card bg-base-200 shadow-sm"><div class="card-body"><div class="rating rating-sm mb-3"><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /><input type="radio" class="mask mask-star-2 bg-warning" checked /></div><p class="italic text-base-content/80">"ROI visible dès le premier mois."</p><div class="mt-4 flex items-center gap-3"><div class="avatar placeholder"><div class="bg-accent text-accent-content rounded-full w-10"><span>C</span></div></div><div><div class="font-semibold">Claire R.</div><div class="text-xs text-base-content/50">Manager</div></div></div></div></div>
    </div>
  </div>
</section>`,
})

// ── FAQ ─────────────────────────────────────────────────────────
editor.Blocks.add('faq-accordion', {
    label: 'FAQ accordéon',
    category: 'FAQ',
    media: svgIcon('faq'),
    content: `<section class="py-20 bg-base-100">
  <div class="max-w-3xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-12">Questions fréquentes</h2>
    <div class="join join-vertical w-full shadow-sm">
      <div class="collapse collapse-arrow join-item border border-base-300"><input type="radio" name="faq" checked /><div class="collapse-title text-lg font-medium">Comment commencer ?</div><div class="collapse-content"><p>Créez votre compte gratuitement, sans carte bancaire requise. L'installation prend moins de 5 minutes.</p></div></div>
      <div class="collapse collapse-arrow join-item border border-base-300"><input type="radio" name="faq" /><div class="collapse-title text-lg font-medium">Quels sont les tarifs ?</div><div class="collapse-content"><p>Nous proposons des plans adaptés à toutes les tailles d'équipes, à partir de 9€/mois. L'essai gratuit de 14 jours vous donne accès à toutes les fonctionnalités.</p></div></div>
      <div class="collapse collapse-arrow join-item border border-base-300"><input type="radio" name="faq" /><div class="collapse-title text-lg font-medium">Puis-je annuler à tout moment ?</div><div class="collapse-content"><p>Oui, vous pouvez annuler votre abonnement à tout moment depuis votre espace client, sans frais de résiliation.</p></div></div>
      <div class="collapse collapse-arrow join-item border border-base-300"><input type="radio" name="faq" /><div class="collapse-title text-lg font-medium">Vos données sont-elles sécurisées ?</div><div class="collapse-content"><p>Toutes les données sont chiffrées en transit et au repos. Nous sommes conformes RGPD et réalisons des audits de sécurité réguliers.</p></div></div>
    </div>
  </div>
</section>`,
})

// ── PRICING ──────────────────────────────────────────────────────
editor.Blocks.add('pricing', {
    label: 'Tarifs',
    category: 'Pricing',
    media: svgIcon('pricing'),
    content: `<section class="py-20 bg-base-200">
  <div class="max-w-6xl mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl font-bold mb-4">Des tarifs simples et transparents</h2>
      <p class="text-base-content/60">Choisissez le plan qui correspond à vos besoins.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
      <div class="card bg-base-100 shadow-md"><div class="card-body"><h3 class="text-xl font-bold">Starter</h3><div class="my-4"><span class="text-4xl font-bold">9€</span><span class="text-base-content/60">/mois</span></div><ul class="space-y-2 mb-6"><li class="flex items-center gap-2"><span class="text-success">✓</span> 5 projets</li><li class="flex items-center gap-2"><span class="text-success">✓</span> 2 utilisateurs</li><li class="flex items-center gap-2"><span class="text-success">✓</span> 10 Go stockage</li></ul><a href="#" class="btn btn-outline w-full">Commencer</a></div></div>
      <div class="card bg-primary text-primary-content shadow-xl scale-105"><div class="card-body"><div class="badge badge-secondary mb-2">Populaire</div><h3 class="text-xl font-bold">Pro</h3><div class="my-4"><span class="text-4xl font-bold">29€</span><span class="text-primary-content/70">/mois</span></div><ul class="space-y-2 mb-6"><li class="flex items-center gap-2"><span>✓</span> Projets illimités</li><li class="flex items-center gap-2"><span>✓</span> 10 utilisateurs</li><li class="flex items-center gap-2"><span>✓</span> 100 Go stockage</li><li class="flex items-center gap-2"><span>✓</span> Support prioritaire</li></ul><a href="#" class="btn bg-white text-primary hover:bg-white/90 border-0 w-full">Commencer</a></div></div>
      <div class="card bg-base-100 shadow-md"><div class="card-body"><h3 class="text-xl font-bold">Enterprise</h3><div class="my-4"><span class="text-4xl font-bold">Sur devis</span></div><ul class="space-y-2 mb-6"><li class="flex items-center gap-2"><span class="text-success">✓</span> Tout illimité</li><li class="flex items-center gap-2"><span class="text-success">✓</span> SSO / SAML</li><li class="flex items-center gap-2"><span class="text-success">✓</span> SLA garanti</li></ul><a href="#" class="btn btn-outline w-full">Nous contacter</a></div></div>
    </div>
  </div>
</section>`,
})

// ── TEAM ─────────────────────────────────────────────────────────
editor.Blocks.add('team', {
    label: 'Équipe',
    category: 'Équipe',
    media: svgIcon('team'),
    content: `<section class="py-20 bg-base-100">
  <div class="max-w-5xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-12">Notre équipe</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
      <div class="text-center"><div class="avatar mb-4"><div class="w-24 rounded-full ring ring-primary ring-offset-2"><img src="https://placehold.co/96x96/e0e7ff/6366f1?text=AM" alt="Alice" /></div></div><div class="font-bold">Alice Martin</div><div class="text-sm text-base-content/60">CEO & Fondatrice</div></div>
      <div class="text-center"><div class="avatar mb-4"><div class="w-24 rounded-full ring ring-secondary ring-offset-2"><img src="https://placehold.co/96x96/fce7f3/ec4899?text=BL" alt="Bob" /></div></div><div class="font-bold">Bob Laurent</div><div class="text-sm text-base-content/60">CTO</div></div>
      <div class="text-center"><div class="avatar mb-4"><div class="w-24 rounded-full ring ring-accent ring-offset-2"><img src="https://placehold.co/96x96/d1fae5/10b981?text=CD" alt="Claire" /></div></div><div class="font-bold">Claire Dubois</div><div class="text-sm text-base-content/60">Design Lead</div></div>
      <div class="text-center"><div class="avatar mb-4"><div class="w-24 rounded-full ring ring-primary ring-offset-2"><img src="https://placehold.co/96x96/fef3c7/f59e0b?text=DP" alt="David" /></div></div><div class="font-bold">David Petit</div><div class="text-sm text-base-content/60">Head of Sales</div></div>
    </div>
  </div>
</section>`,
})

// ── GALLERY ───────────────────────────────────────────────────────
editor.Blocks.add('gallery', {
    label: 'Galerie',
    category: 'Médias',
    media: svgIcon('gallery'),
    content: `<section class="py-16 bg-base-100">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-10">Galerie</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
      <img src="https://placehold.co/400x300/e0e7ff/6366f1?text=1" class="rounded-xl w-full h-48 object-cover" />
      <img src="https://placehold.co/400x300/fce7f3/ec4899?text=2" class="rounded-xl w-full h-48 object-cover" />
      <img src="https://placehold.co/400x300/d1fae5/10b981?text=3" class="rounded-xl w-full h-48 object-cover" />
      <img src="https://placehold.co/400x300/fef3c7/f59e0b?text=4" class="rounded-xl w-full h-48 object-cover" />
      <img src="https://placehold.co/400x300/f0fdf4/22c55e?text=5" class="rounded-xl w-full h-48 object-cover" />
      <img src="https://placehold.co/400x300/eff6ff/3b82f6?text=6" class="rounded-xl w-full h-48 object-cover" />
    </div>
  </div>
</section>`,
})

// ── TEXT BLOCK ────────────────────────────────────────────────────
editor.Blocks.add('text-block', {
    label: 'Bloc texte',
    category: 'Contenu',
    media: svgIcon('text'),
    content: `<section class="py-16 bg-base-100">
  <div class="max-w-3xl mx-auto px-6 prose prose-lg">
    <h2>Titre de la section</h2>
    <p>Insérez ici votre contenu texte. Vous pouvez écrire des paragraphes, des listes, des citations, etc. Le texte est entièrement éditable dans le builder.</p>
    <ul><li>Premier point important</li><li>Deuxième point</li><li>Troisième point</li></ul>
    <p>Continuez avec un autre paragraphe pour développer votre message.</p>
  </div>
</section>`,
})

// ── ALERT/BANNER ──────────────────────────────────────────────────
editor.Blocks.add('alert-banner', {
    label: 'Bannière alerte',
    category: 'Contenu',
    media: svgIcon('alert'),
    content: `<div class="alert alert-info shadow-lg max-w-4xl mx-auto my-4">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
  <div><h3 class="font-bold">Information importante</h3><div class="text-xs">Votre message ici. Modifier le type de classe pour changer la couleur (alert-info, alert-success, alert-warning, alert-error).</div></div>
  <a href="#" class="btn btn-sm">En savoir plus</a>
</div>`,
})

// ── CARD GRID ─────────────────────────────────────────────────────
editor.Blocks.add('card-grid', {
    label: 'Grille de cartes',
    category: 'Contenu',
    media: svgIcon('cards'),
    content: `<section class="py-16 bg-base-200">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-4xl font-bold text-center mb-10">Nos dernières actualités</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow"><figure><img src="https://placehold.co/400x200/e0e7ff/6366f1?text=Article+1" alt="Article 1" class="w-full h-48 object-cover" /></figure><div class="card-body"><div class="badge badge-primary mb-2">Catégorie</div><h3 class="card-title text-lg">Titre de l'article</h3><p class="text-base-content/70 text-sm">Résumé court de l'article pour donner envie de lire la suite.</p><div class="card-actions justify-end mt-4"><a href="#" class="btn btn-primary btn-sm">Lire</a></div></div></div>
      <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow"><figure><img src="https://placehold.co/400x200/d1fae5/10b981?text=Article+2" alt="Article 2" class="w-full h-48 object-cover" /></figure><div class="card-body"><div class="badge badge-secondary mb-2">Actualité</div><h3 class="card-title text-lg">Deuxième article</h3><p class="text-base-content/70 text-sm">Résumé court de l'article pour donner envie de lire la suite.</p><div class="card-actions justify-end mt-4"><a href="#" class="btn btn-secondary btn-sm">Lire</a></div></div></div>
      <div class="card bg-base-100 shadow-md hover:shadow-lg transition-shadow"><figure><img src="https://placehold.co/400x200/fce7f3/ec4899?text=Article+3" alt="Article 3" class="w-full h-48 object-cover" /></figure><div class="card-body"><div class="badge badge-accent mb-2">Tutoriel</div><h3 class="card-title text-lg">Troisième article</h3><p class="text-base-content/70 text-sm">Résumé court de l'article pour donner envie de lire la suite.</p><div class="card-actions justify-end mt-4"><a href="#" class="btn btn-accent btn-sm">Lire</a></div></div></div>
    </div>
  </div>
</section>`,
})

// ── IMAGE + TEXT ───────────────────────────────────────────────────
editor.Blocks.add('image-text', {
    label: 'Image + Texte',
    category: 'Contenu',
    media: svgIcon('image_text'),
    content: `<section class="py-16 bg-base-100">
  <div class="max-w-5xl mx-auto px-6 flex flex-col md:flex-row items-center gap-12">
    <img src="https://placehold.co/480x360/e0e7ff/6366f1?text=Illustration" class="rounded-2xl shadow-lg w-full md:w-1/2 object-cover" />
    <div class="flex-1">
      <div class="badge badge-primary mb-3">Notre approche</div>
      <h2 class="text-3xl font-bold mb-4">Un sous-titre percutant</h2>
      <p class="text-base-content/70 mb-6">Décrivez ici votre approche, vos valeurs ou vos méthodes de façon concrète. Montrez ce qui vous différencie de la concurrence.</p>
      <ul class="space-y-2 mb-6"><li class="flex items-center gap-2"><span class="text-primary">✓</span> Point fort numéro 1</li><li class="flex items-center gap-2"><span class="text-primary">✓</span> Point fort numéro 2</li><li class="flex items-center gap-2"><span class="text-primary">✓</span> Point fort numéro 3</li></ul>
      <a href="#" class="btn btn-primary">Découvrir</a>
    </div>
  </div>
</section>`,
})

/* ──────────────────────────────────────────────────────────────
   Load initial content
────────────────────────────────────────────────────────────── */
if (cfg.initialContent && Array.isArray(cfg.initialContent?.pages)) {
    editor.loadProjectData(cfg.initialContent)
} else if (cfg.initialContent && cfg.initialContent.html !== undefined) {
    // Legacy or partial save
    editor.setComponents(cfg.initialContent.html || '')
    editor.setStyle(cfg.initialContent.css || '')
}

/* ──────────────────────────────────────────────────────────────
   Custom toolbar — panels wired up via DOM
────────────────────────────────────────────────────────────── */

// Tabs — left panel
document.querySelectorAll('#pb-panel-left .pb-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('#pb-panel-left .pb-tab').forEach(t => t.classList.remove('active'))
        document.querySelectorAll('#pb-panel-left .pb-panel-content').forEach(c => c.classList.remove('active'))
        tab.classList.add('active')
        document.getElementById(`pb-${tab.dataset.tab}`)?.classList.add('active')
    })
})

// Tabs — right panel
document.querySelectorAll('#pb-panel-right .pb-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('#pb-panel-right .pb-tab').forEach(t => t.classList.remove('active'))
        document.querySelectorAll('#pb-panel-right .pb-panel-content').forEach(c => c.classList.remove('active'))
        tab.classList.add('active')
        document.getElementById(`pb-${tab.dataset.tab}`)?.classList.add('active')
    })
})

// Device switcher
document.querySelectorAll('.pb-btn-device').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pb-btn-device').forEach(b => b.classList.remove('active'))
        btn.classList.add('active')
        const deviceMap = { desktop: 'Desktop', tablet: 'Tablet', mobile: 'Mobile' }
        editor.setDevice(deviceMap[btn.dataset.device] || 'Desktop')
    })
})

// Undo / Redo
document.getElementById('pb-undo')?.addEventListener('click', () => editor.runCommand('core:undo'))
document.getElementById('pb-redo')?.addEventListener('click', () => editor.runCommand('core:redo'))

/* ──────────────────────────────────────────────────────────────
   Save
────────────────────────────────────────────────────────────── */
function showToast(message, type = 'success') {
    const toast = document.getElementById('pb-toast')
    if (!toast) return
    toast.textContent = message
    toast.className = `pb-toast pb-toast-${type}`
    setTimeout(() => toast.classList.add('hidden'), 3000)
}

async function savePage() {
    const saveBtn    = document.getElementById('pb-save')
    const saveStatus = document.getElementById('pb-save-status')

    saveBtn.disabled = true
    saveStatus.textContent = 'Sauvegarde...'

    const projectData = editor.getProjectData()
    const html        = editor.getHtml()
    const css         = editor.getCss()

    try {
        const response = await fetch(cfg.saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': cfg.csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                builder_content: projectData,
                builder_html: html,
                builder_css: css,
            }),
        })

        const data = await response.json()

        if (data.success) {
            saveStatus.textContent = 'Sauvegardé ✓'
            showToast('Page sauvegardée avec succès !')

            // Update the "Voir" link if slug was generated
            if (data.public_url) {
                const viewLink = document.querySelector('a[href*="page.show"], a[href*="/p/"]')
                if (viewLink) viewLink.href = data.public_url
            }
        } else {
            saveStatus.textContent = 'Erreur'
            showToast('Erreur lors de la sauvegarde.', 'error')
        }
    } catch (e) {
        saveStatus.textContent = 'Erreur'
        showToast('Erreur réseau.', 'error')
    } finally {
        saveBtn.disabled = false
        setTimeout(() => { saveStatus.textContent = '' }, 5000)
    }
}

document.getElementById('pb-save')?.addEventListener('click', savePage)

// Ctrl+S shortcut
document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault()
        savePage()
    }
})

/* ──────────────────────────────────────────────────────────────
   Asset Manager — wire up media upload
────────────────────────────────────────────────────────────── */
editor.on('run:open-assets', () => {
    // GrapeJS handles its own upload UI; we just configure the endpoint
})

editor.AssetManager.config.upload      = cfg.mediaUrl
editor.AssetManager.config.uploadName  = 'files'
editor.AssetManager.config.headers     = { 'X-CSRF-TOKEN': cfg.csrfToken }
editor.AssetManager.config.multiUpload = true
editor.AssetManager.config.autoAdd     = true
