<svg viewBox="0 0 200 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <!-- Logo Circle -->
  <defs>
    <linearGradient id="dollarGradientFull" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#059669;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#047857;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#1F2937;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#374151;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Logo Circle -->
  <circle cx="24" cy="24" r="22" fill="url(#dollarGradientFull)" stroke="#065F46" stroke-width="2"/>
  <circle cx="24" cy="24" r="20" fill="none" stroke="#FFFFFF" stroke-width="0.5" opacity="0.3"/>
  
  <!-- Dollar Symbol -->
  <g transform="translate(24, 24)">
    <line x1="0" y1="-14" x2="0" y2="14" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round"/>
    <path d="M -5 -7 Q -5 -10 -2 -10 L 3 -10 Q 6 -10 6 -7 Q 6 -4 3 -4 L -3 -4 Q -6 -4 -6 0 Q -6 3 -3 3 L 2 3 Q 5 3 5 6 Q 5 10 2 10 L -3 10 Q -6 10 -6 7" 
          stroke="#FFFFFF" 
          stroke-width="2.5" 
          fill="none" 
          stroke-linecap="round" 
          stroke-linejoin="round"/>
  </g>
  
  <!-- Shine Effect -->
  <ellipse cx="18" cy="16" rx="2.5" ry="5" fill="#FFFFFF" opacity="0.2" transform="rotate(-45 18 16)"/>
  
  <!-- Text -->
  <text x="58" y="20" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="url(#textGradient)">LPJ</text>
  <text x="58" y="35" font-family="Arial, sans-serif" font-size="12" font-weight="600" fill="#6B7280">Financial System</text>
</svg>