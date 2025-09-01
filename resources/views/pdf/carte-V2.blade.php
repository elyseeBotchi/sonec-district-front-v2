{{-- resources/views/cards/half-a4.blade.php --}}
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Carte de stationnement</title>
<style>
  /* ====== Mise en page : A4 portrait, moitié supérieure ====== */
  @page { size: A4 portrait; margin: 0; }
  html, body { margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; }

  .half { position: relative; width: 210mm; height: 148.5mm; box-sizing: border-box; overflow: hidden; }
  .pad { padding: 8mm; }

  /* ====== Calques ====== */
  .layer { position: absolute; inset: 0; }
  .content { position: relative; width: 100%; height: 100%; }

  /* ====== Pantographe Fallback CSS (si SVG patterns pas supportés) ====== */
  .panto-fallback {
    position: absolute; inset: 0;
    opacity: .05; color: #000; font-size: 8pt; line-height: 11pt;
    overflow: hidden; pointer-events: none;
  }
  .panto-fallback p { margin: 0; white-space: nowrap; }

  /* ====== Micro-texte ====== */
  .micro { position: absolute; left: 8mm; right: 8mm; color: #111; font-size: 3.5pt; letter-spacing: 0.2pt; }
  .micro.top { top: 6mm; }
  .micro.fold { top: calc(148.5mm - 6mm); } /* proche de la ligne médiane */

  /* ====== Ghost (immatriculation diagonale) ====== */
  .ghost {
    position: absolute; top: 22mm; left: 0; width: 210mm;
    text-align: center; font-weight: 700;
    color: #000; opacity: .06; font-size: 46pt;
    transform: rotate(15deg);
    pointer-events: none; user-select: none;
  }

  /* ====== QR ====== */
  .qr-wrap { position: absolute; top: 12mm; right: 12mm; width: 40mm; text-align: center; }
  .qr-img { display: block; width: 40mm; height: 40mm; image-rendering: pixelated; }
  .qr-label { font-size: 8pt; color: #111; margin-top: 2mm; }
  .qr-kid { font-size: 7pt; color: #6B7280; }

  /* Quiet zone : rectangle blanc sous le QR pour éviter trame derrière */
  .qr-quiet {
    position: absolute; top: 12mm; right: 12mm; width: 40mm; height: 40mm;
    background: #fff; z-index: 2;
  }
  .qr-wrap { z-index: 3; }

  /* ====== Trace d’impression ====== */
  .trace { position: absolute; left: 8mm; bottom: 6mm; font-size: 7pt; color: #6B7280; }

  /* ====== Habillage optionnel de ton gabarit d’origine ======
     Place ici tes blocs (tableaux/labels) si tu veux superposer exactement.
  ============================================================ */
</style>
</head>
<body>

{{-- ====== Moitié supérieure A4 ====== --}}
<div class="half">
  <div class="content pad">

    {{-- ========= 1) Pantographe SVG (latent "COPIE") =========
         Trois variantes A/B/C pour calibrer ta repro. Choisis via $calibration.
         Dompdf a un support SVG correct ; si jamais pattern non rendu, le fallback CSS prendra le relais.
    --}}
    @php
      $cal = strtoupper($calibration ?? 'A'); // 'A' | 'B' | 'C'
      $showPantograph = ($showPantograph ?? true);
      $showMicrotext  = ($showMicrotext  ?? true);
      $showGhost      = ($showGhost      ?? true);
      $serialSafe = e($serial ?? 'DIS-TSA-25-2907-3200503495-X');
      $plateSafe  = e($plate  ?? 'AA-932-CR-01');
      $kidSafe    = e($kid    ?? '25A');
      $codeShort  = e($code_short ?? 'K7-MQ4Z');
      $printedBy  = e($printed_by ?? 'Guichet G3');
      $agentSafe  = e($agent ?? 'A17');
      $printedAt  = e($printed_at ?? now()->format('Y-m-d H:i'));
    @endphp

    @if($showPantograph)
      {{-- SVG patterns : A/B/C (rayon/espacement/alpha) --}}
      <svg class="layer" width="210mm" height="148.5mm" viewBox="0 0 210 148.5" xmlns="http://www.w3.org/2000/svg">
        <defs>
          @if($cal === 'A')
            <pattern id="p_bg" width="0.80" height="0.80" patternUnits="userSpaceOnUse">
              <circle cx="0.40" cy="0.40" r="0.09" fill="rgba(0,0,0,0.06)"/>
            </pattern>
            <pattern id="p_word" width="0.80" height="0.80" patternUnits="userSpaceOnUse">
              <circle cx="0.40" cy="0.40" r="0.12" fill="rgba(0,0,0,0.09)"/>
            </pattern>
          @elseif($cal === 'B')
            <pattern id="p_bg" width="0.75" height="0.75" patternUnits="userSpaceOnUse">
              <circle cx="0.375" cy="0.375" r="0.08" fill="rgba(0,0,0,0.055)"/>
            </pattern>
            <pattern id="p_word" width="0.75" height="0.75" patternUnits="userSpaceOnUse">
              <circle cx="0.375" cy="0.375" r="0.11" fill="rgba(0,0,0,0.085)"/>
            </pattern>
          @else  {{-- C --}}
            <pattern id="p_bg" width="0.85" height="0.85" patternUnits="userSpaceOnUse">
              <circle cx="0.425" cy="0.425" r="0.10" fill="rgba(0,0,0,0.065)"/>
            </pattern>
            <pattern id="p_word" width="0.85" height="0.85" patternUnits="userSpaceOnUse">
              <circle cx="0.425" cy="0.425" r="0.13" fill="rgba(0,0,0,0.095)"/>
            </pattern>
          @endif
        </defs>

        <!-- Fond pointillé -->
        <rect x="0" y="0" width="210" height="148.5" fill="url(#p_bg)"/>

        <!-- Mot latent (quasi invisible sur l’original, ressort en repro) -->
        <g style="opacity:0.85">
          <text x="105" y="55" text-anchor="middle"
                font-size="26" font-weight="700"
                fill="url(#p_word)" letter-spacing="1.5">COPIE</text>
          <text x="105" y="105" text-anchor="middle"
                font-size="26" font-weight="700"
                fill="url(#p_word)" letter-spacing="1.5">COPIE</text>
        </g>
      </svg>

      {{-- Fallback CSS si jamais le moteur PDF ignore les patterns --}}
      <div class="panto-fallback" aria-hidden="true">
        @for($i=0; $i<40; $i++)
          <p>DISTRICT • VALID • OFFICIEL • {{ $serialSafe }} • {{ $plateSafe }} • DISTRICT • VALID • OFFICIEL • {{ $serialSafe }} • {{ $plateSafe }} •</p>
        @endfor
      </div>
    @endif

    {{-- ========= 2) Micro-texte ========= --}}
    @if($showMicrotext)
      <div class="micro top">
        RÉPUBLIQUE • DISTRICT • SÉRIE {{ $serialSafe }} • PLAQUE {{ $plateSafe }} • RÉPUBLIQUE • DISTRICT • SÉRIE {{ $serialSafe }} • PLAQUE {{ $plateSafe }} •
      </div>
      <div class="micro fold">
        RÉPUBLIQUE • DISTRICT • SÉRIE {{ $serialSafe }} • PLAQUE {{ $plateSafe }} • RÉPUBLIQUE • DISTRICT • SÉRIE {{ $serialSafe }} • PLAQUE {{ $plateSafe }} •
      </div>
    @endif

    {{-- ========= 3) Ghost (immatriculation diagonale) ========= --}}
    @if($showGhost)
      <div class="ghost">{{ $plateSafe }}</div>
    @endif

    {{-- ========= 4) Quiet zone blanche sous le QR ========= --}}
    <div class="qr-quiet"></div>

    {{-- ========= 5) QR + Code court + KID ========= --}}
    <div class="qr-wrap">
      @if(!empty($qrPng))
        <img class="qr-img" src="data:image/png;base64,{{ $qrPng }}" alt="QR signé">
      @else
        {{-- Placeholder si pas de QR fourni --}}
        <div style="width:40mm;height:40mm;border:1pt solid #333;display:flex;align-items:center;justify-content:center;">QR</div>
      @endif
      <div class="qr-label">CODE COURT : {{ $codeShort }}</div>
      <div class="qr-kid">KID : {{ $kidSafe }}</div>
    </div>

    {{-- ========= 6) Trace d’impression ========= --}}
    <div class="trace">Imprimé par {{ $printedBy }} • Agent {{ $agentSafe }} • {{ $printedAt }}</div>

    {{-- ========= PLACE TON GABARIT D’ORIGINE ICI SI BESOIN =========
      Par ex. tes tableaux/labels exacts (nom, montant, dates, etc.)
      <div style="position:absolute; left:20mm; top:20mm; font-size:10pt;">...</div>
    --}}
  </div>
</div>

{{-- Moitié inférieure laissée vide (pour “moitié d’A4”) --}}
<div style="height:148.5mm"></div>

</body>
</html>
