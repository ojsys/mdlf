<?php /* Block: hero — heading is intentionally raw HTML (allows <em>). */ ?>
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow on-dark"><?= e($b['eyebrow']) ?></span>
      <h1><?= $b['heading'] ?></h1>
      <p class="lead"><?= e($b['lead']) ?></p>
      <div class="hero-cta">
        <a class="btn btn-primary" href="<?= url($b['cta_link']) ?>">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"></path>
            <path d="m12 5 7 7-7 7"></path>
          </svg>
          <?= e($b['cta_text']) ?>
        </a>
        <a class="btn btn-ghost on-dark" href="<?= url($b['secondary_link']) ?>"><?= e($b['secondary_text']) ?></a>
      </div>
      <p class="hero-verse">&ldquo;<?= e($b['verse']) ?>&rdquo;<cite><?= e($b['verse_ref']) ?></cite></p>
    </div>
    <div class="hero-art">
      <!-- Signature: the multiplication motif — one disciple reproducing many -->
      <svg viewBox="0 0 440 360" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="One disciple multiplying many">
        <defs>
          <linearGradient id="g" x1="0" x2="1"><stop offset="0" stop-color="#F0D89A"/><stop offset="1" stop-color="#D7A33A"/></linearGradient>
        </defs>
        <!-- links -->
        <g stroke="rgba(240,216,154,.35)" stroke-width="1.5" fill="none">
          <path d="M220 60 C 220 110, 120 110, 120 150"/>
          <path d="M220 60 C 220 110, 320 110, 320 150"/>
          <path d="M120 150 C 120 200, 70 200, 70 240"/>
          <path d="M120 150 C 120 200, 170 200, 170 240"/>
          <path d="M320 150 C 320 200, 270 200, 270 240"/>
          <path d="M320 150 C 320 200, 370 200, 370 240"/>
          <path d="M70 240 L 50 300 M70 240 L 90 300"/>
          <path d="M170 240 L 150 300 M170 240 L 190 300"/>
          <path d="M270 240 L 250 300 M270 240 L 290 300"/>
          <path d="M370 240 L 350 300 M370 240 L 390 300"/>
        </g>
        <!-- generation 4 (small) -->
        <g fill="rgba(240,216,154,.55)">
          <circle cx="50" cy="306" r="5"/><circle cx="90" cy="306" r="5"/>
          <circle cx="150" cy="306" r="5"/><circle cx="190" cy="306" r="5"/>
          <circle cx="250" cy="306" r="5"/><circle cx="290" cy="306" r="5"/>
          <circle cx="350" cy="306" r="5"/><circle cx="390" cy="306" r="5"/>
        </g>
        <!-- generation 3 -->
        <g fill="#F0D89A"><circle cx="70" cy="240" r="9"/><circle cx="170" cy="240" r="9"/><circle cx="270" cy="240" r="9"/><circle cx="370" cy="240" r="9"/></g>
        <!-- generation 2 -->
        <g fill="url(#g)"><circle cx="120" cy="150" r="13"/><circle cx="320" cy="150" r="13"/></g>
        <!-- the first disciple -->
        <circle cx="220" cy="60" r="22" fill="url(#g)"/>
        <circle cx="220" cy="60" r="22" fill="none" stroke="#FBF8F1" stroke-width="2"/>
        <path d="M220 70V52 M220 56c0-5 4-8 9-8 0 5-4 8-9 8Z M220 60c0-5-4-8-9-8 0 5 4 8 9 8Z" fill="#0E1B33"/>
      </svg>
      <span class="cap">2 Timothy 2:2 — disciples making disciples</span>
    </div>
  </div>
</section>
