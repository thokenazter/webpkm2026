<svg viewBox="0 0 280 48" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
  <defs>
    <!-- Main Background Gradient -->
    <linearGradient id="bgGradientFull" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#10B981;stop-opacity:1" />
      <stop offset="30%" style="stop-color:#059669;stop-opacity:1" />
      <stop offset="70%" style="stop-color:#047857;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#064E3B;stop-opacity:1" />
    </linearGradient>
    
    <!-- Inner Circle Gradient -->
    <linearGradient id="innerGradientFull" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#34D399;stop-opacity:0.3" />
      <stop offset="100%" style="stop-color:#10B981;stop-opacity:0.1" />
    </linearGradient>
    
    <!-- Dollar Symbol Gradient -->
    <linearGradient id="dollarGradientFull" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#FFFFFF;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#F0FDF4;stop-opacity:0.95" />
      <stop offset="100%" style="stop-color:#ECFDF5;stop-opacity:0.9" />
    </linearGradient>
    
    <!-- Radial Gradient for Glow -->
    <radialGradient id="glowGradientFull" cx="50%" cy="30%" r="60%">
      <stop offset="0%" style="stop-color:#FFFFFF;stop-opacity:0.4" />
      <stop offset="70%" style="stop-color:#FFFFFF;stop-opacity:0.1" />
      <stop offset="100%" style="stop-color:#FFFFFF;stop-opacity:0" />
    </radialGradient>
    
    <!-- Text Gradients -->
    <linearGradient id="textGradient" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#1F2937;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#374151;stop-opacity:1" />
    </linearGradient>
    
    <linearGradient id="subtextGradient" x1="0%" y1="0%" x2="100%" y2="0%">
      <stop offset="0%" style="stop-color:#059669;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#047857;stop-opacity:1" />
    </linearGradient>
  </defs>
  
  <!-- Outer Shadow Circle -->
  <circle cx="24" cy="26" r="22" fill="#000000" opacity="0.08"/>
  
  <!-- Main Background Circle -->
  <circle cx="24" cy="24" r="22" fill="url(#bgGradientFull)" stroke="#065F46" stroke-width="1.5"/>
  
  <!-- Inner Highlight Circle -->
  <circle cx="24" cy="24" r="20" fill="url(#innerGradientFull)"/>
  
  <!-- Glow Effect -->
  <circle cx="24" cy="24" r="18" fill="url(#glowGradientFull)"/>
  
  <!-- Inner Border -->
  <circle cx="24" cy="24" r="19" fill="none" stroke="#FFFFFF" stroke-width="0.5" opacity="0.4"/>
  
  <!-- Dollar Symbol -->
  <g transform="translate(24, 24)">
    <!-- Vertical Line Shadow -->
    <line x1="0.5" y1="-14.5" x2="0.5" y2="14.5" stroke="#047857" stroke-width="2.8" stroke-linecap="round" opacity="0.3"/>
    
    <!-- Main Vertical Line -->
    <line x1="0" y1="-15" x2="0" y2="15" stroke="url(#dollarGradientFull)" stroke-width="2.6" stroke-linecap="round"/>
    
    <!-- S Shape Shadow -->
    <path d="M -5.5 -7.5 Q -5.5 -11.5 -1.5 -11.5 L 3.5 -11.5 Q 7.5 -11.5 7.5 -7.5 Q 7.5 -3.5 3.5 -3.5 L -3.5 -3.5 Q -7.5 -3.5 -7.5 0.5 Q -7.5 3.5 -3.5 3.5 L 1.5 3.5 Q 5.5 3.5 5.5 7.5 Q 5.5 11.5 1.5 11.5 L -3.5 11.5 Q -7.5 11.5 -7.5 7.5" 
          stroke="#047857" 
          stroke-width="3" 
          fill="none" 
          stroke-linecap="round" 
          stroke-linejoin="round"
          opacity="0.3"/>
    
    <!-- Main S Shape -->
    <path d="M -5 -7 Q -5 -11 -1 -11 L 3 -11 Q 7 -11 7 -7 Q 7 -3 3 -3 L -3 -3 Q -7 -3 -7 1 Q -7 4 -3 4 L 1 4 Q 5 4 5 8 Q 5 11 1 11 L -3 11 Q -7 11 -7 8" 
          stroke="url(#dollarGradientFull)" 
          stroke-width="2.6" 
          fill="none" 
          stroke-linecap="round" 
          stroke-linejoin="round"/>
  </g>
  
  <!-- Top Shine Effect -->
  <ellipse cx="17" cy="15" rx="3.5" ry="7" fill="#FFFFFF" opacity="0.3" transform="rotate(-45 17 15)"/>
  
  <!-- Secondary Shine -->
  <ellipse cx="32" cy="32" rx="2" ry="4" fill="#FFFFFF" opacity="0.15" transform="rotate(45 32 32)"/>
  
  <!-- Sparkle Effects -->
  <circle cx="35" cy="13" r="0.8" fill="#FFFFFF" opacity="0.6"/>
  <circle cx="13" cy="35" r="0.6" fill="#FFFFFF" opacity="0.4"/>
  
  <!-- Text -->
  <text x="62" y="21" font-family="system-ui, -apple-system, sans-serif" font-size="18" font-weight="700" fill="url(#textGradient)">LPJ BOK System</text>
  <text x="62" y="36" font-family="system-ui, -apple-system, sans-serif" font-size="11" font-weight="600" fill="url(#subtextGradient)">Financial Management</text>
</svg>