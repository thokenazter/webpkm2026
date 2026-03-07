<svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <!-- Background Circle with Gradient -->
  <defs>
    <linearGradient id="dollarGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#059669;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#047857;stop-opacity:1" />
    </linearGradient>
    <linearGradient id="dollarSymbolGradient" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#FFFFFF;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#F0FDF4;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Main Circle -->
  <circle cx="24" cy="24" r="22" fill="url(#dollarGradient)" stroke="#065F46" stroke-width="2"/>
  
  <!-- Inner Shadow Effect -->
  <circle cx="24" cy="24" r="20" fill="none" stroke="#FFFFFF" stroke-width="0.5" opacity="0.3"/>
  
  <!-- Dollar Symbol -->
  <g transform="translate(24, 24)">
    <!-- Vertical Lines -->
    <line x1="0" y1="-16" x2="0" y2="16" stroke="url(#dollarSymbolGradient)" stroke-width="2.5" stroke-linecap="round"/>
    
    <!-- S Shape -->
    <path d="M -6 -8 Q -6 -12 -2 -12 L 4 -12 Q 8 -12 8 -8 Q 8 -4 4 -4 L -4 -4 Q -8 -4 -8 0 Q -8 4 -4 4 L 2 4 Q 6 4 6 8 Q 6 12 2 12 L -4 12 Q -8 12 -8 8" 
          stroke="url(#dollarSymbolGradient)" 
          stroke-width="2.5" 
          fill="none" 
          stroke-linecap="round" 
          stroke-linejoin="round"/>
  </g>
  
  <!-- Shine Effect -->
  <ellipse cx="18" cy="16" rx="3" ry="6" fill="#FFFFFF" opacity="0.2" transform="rotate(-45 18 16)"/>
</svg>