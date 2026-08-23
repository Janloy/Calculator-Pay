<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Pay-Per-Calc 3000</title>
  <style>
    :root {
      --bg: #030712;
      --surface: rgba(17, 24, 39, 0.75);
      --surface-border: rgba(255, 255, 255, 0.1);
      --surface-hover: rgba(255, 255, 255, 0.14);
      --accent: #6366f1;
      --accent-glow: rgba(99, 102, 241, 0.4);
      --text: #f9fafb;
      --text-muted: #9ca3af;
      --success: #10b981;
      --success-glow: rgba(16, 185, 129, 0.35);
      
      /* Gateway Backgrounds */
      --gcash: #ffffff;
      --maya: #000000;
      --paypal: #ffffff;
      --card: #1e293b;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      user-select: none;
      -webkit-tap-highlight-color: transparent;
    }

    body {
      background-color: var(--bg);
      background-image: 
        radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.22) 0%, transparent 75%),
        radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.15) 0%, transparent 60%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      color: var(--text);
      padding: 16px;
    }

    .calc-container {
      width: 100%;
      max-width: 410px;
      background: var(--surface);
      backdrop-filter: blur(28px);
      border: 1px solid var(--surface-border);
      border-radius: 36px;
      padding: 24px;
      box-shadow: 0 32px 64px -16px rgba(0, 0, 0, 0.7);
      display: flex;
      flex-direction: column;
      gap: 18px;
    }

    .calc-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .brand {
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #9ca3af;
    }

    .info-btn {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid var(--surface-border);
      color: var(--text-muted);
      width: 22px;
      height: 22px;
      border-radius: 50%;
      font-size: 11px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }

    .info-btn:hover {
      background: var(--accent);
      color: #fff;
      border-color: var(--accent);
    }

    .status-tag {
      font-size: 10px;
      font-weight: 700;
      background: rgba(99, 102, 241, 0.18);
      color: #a5b4fc;
      padding: 4px 12px;
      border-radius: 20px;
      border: 1px solid rgba(99, 102, 241, 0.35);
    }

    .display {
      background: rgba(3, 7, 18, 0.7);
      border: 1px solid var(--surface-border);
      border-radius: 28px;
      padding: 20px 24px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      align-items: flex-end;
      min-height: 115px;
    }

    .expression {
      font-size: 15px;
      color: var(--text-muted);
      min-height: 22px;
    }

    .result {
      font-size: 42px;
      font-weight: 700;
      color: var(--text);
      line-height: 1.1;
      margin-top: 4px;
      max-width: 100%;
      transition: color 0.3s ease;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    .result::-webkit-scrollbar {
      display: none;
    }

    .result.unlocked {
      color: #34d399;
      text-shadow: 0 0 16px rgba(52, 211, 153, 0.3);
    }

    .keypad {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }

    .btn {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid var(--surface-border);
      border-radius: 22px;
      min-height: 64px;
      font-size: 22px;
      font-weight: 600;
      color: var(--text);
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all 0.15s ease;
    }

    .btn:active {
      transform: scale(0.94);
      background: var(--surface-hover);
    }

    .btn.op {
      background: rgba(99, 102, 241, 0.14);
      color: #a5b4fc;
      border-color: rgba(99, 102, 241, 0.28);
    }

    .btn.clear {
      background: rgba(239, 68, 68, 0.14);
      color: #fca5a5;
      border-color: rgba(239, 68, 68, 0.28);
    }

    .btn.equals {
      background: var(--accent);
      color: #ffffff;
      grid-column: span 2;
      box-shadow: 0 8px 24px var(--accent-glow);
    }

    /* Modal Styling */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(16px);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 16px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.25s ease;
      z-index: 100;
    }

    .modal-overlay.active {
      opacity: 1;
      pointer-events: auto;
    }

    .modal {
      background: #0d1322;
      border: 1px solid rgba(255, 255, 255, 0.14);
      border-radius: 36px;
      width: 100%;
      max-width: 420px;
      padding: 26px;
      transform: translateY(24px);
      transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal-overlay.active .modal {
      transform: translateY(0);
    }

    .modal-header {
      text-align: center;
      margin-bottom: 18px;
    }

    .modal-title { font-size: 21px; font-weight: 700; }
    .modal-sub { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

    .receipt {
      background: rgba(255, 255, 255, 0.02);
      border: 1px dashed rgba(255, 255, 255, 0.14);
      border-radius: 20px;
      padding: 14px 16px;
      margin-bottom: 20px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .receipt-row {
      display: flex;
      justify-content: space-between;
      font-size: 13px;
      color: var(--text-muted);
    }

    .receipt-row.total {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 8px;
      font-weight: 700;
      font-size: 15px;
      color: var(--text);
    }

    .payment-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .pay-card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--surface-border);
      border-radius: 20px;
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .pay-card:hover {
      border-color: rgba(255, 255, 255, 0.3);
      background: rgba(255, 255, 255, 0.06);
    }

    .pay-card:active { transform: scale(0.95); }

    /* Logo Container Styling */
    .brand-icon {
      width: 56px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 6px;
      overflow: hidden;
    }

    .brand-icon.gcash { background: var(--gcash); }
    .brand-icon.maya { background: var(--maya); border: 1px solid rgba(255,255,255,0.15); }
    .brand-icon.paypal { background: var(--paypal); }
    .brand-icon.card { background: var(--card); border: 1px solid rgba(255,255,255,0.1); }

    .brand-icon img, .brand-icon svg {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .cancel-btn {
      width: 100%;
      margin-top: 14px;
      background: transparent;
      border: none;
      color: var(--text-muted);
      font-size: 13px;
      cursor: pointer;
    }

    /* Card Form Styles */
    .card-form-view {
      display: none;
      flex-direction: column;
      gap: 14px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .form-group label {
      font-size: 12px;
      color: var(--text-muted);
      font-weight: 600;
    }

    .form-input {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--surface-border);
      border-radius: 14px;
      padding: 12px 14px;
      color: #ffffff;
      font-size: 14px;
      outline: none;
      user-select: text;
      transition: border-color 0.2s ease;
      letter-spacing: 0.5px;
    }

    .form-input:focus {
      border-color: var(--accent);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .processing-view {
      display: none;
      flex-direction: column;
      align-items: center;
      padding: 24px 0;
      gap: 16px;
    }

    .spinner {
      width: 52px;
      height: 52px;
      border: 4px solid rgba(255, 255, 255, 0.1);
      border-top-color: var(--accent);
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .success-view {
      display: none;
      flex-direction: column;
      align-items: center;
      padding: 20px 0;
      text-align: center;
    }

    .success-icon-box {
      width: 80px;
      height: 80px;
      background: rgba(16, 185, 129, 0.15);
      border: 2px solid var(--success);
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 18px;
      box-shadow: 0 0 24px var(--success-glow);
      animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes popIn {
      0% { transform: scale(0); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    .checkmark-svg { width: 42px; height: 42px; }

    .checkmark-path {
      stroke: var(--success);
      stroke-width: 4;
      stroke-dasharray: 50;
      stroke-dashoffset: 50;
      stroke-linecap: round;
      stroke-linejoin: round;
      fill: none;
      animation: strokeDraw 0.5s 0.2s ease-in-out forwards;
    }

    @keyframes strokeDraw { to { stroke-dashoffset: 0; } }

    .unlocked-value {
      font-size: 32px;
      font-weight: 800;
      color: #ffffff;
      margin: 8px 0;
    }

    .submit-btn {
      background: var(--accent);
      border: none;
      border-radius: 16px;
      padding: 14px;
      font-size: 15px;
      font-weight: 700;
      color: #ffffff;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
    }

    /* About Modal Styles */
    .about-view {
      display: none;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 12px;
    }

    .dev-avatar {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--accent), #818cf8);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      font-weight: bold;
      color: white;
      box-shadow: 0 0 20px var(--accent-glow);
      margin-bottom: 4px;
    }

    .dev-badge {
      background: rgba(99, 102, 241, 0.2);
      border: 1px solid rgba(99, 102, 241, 0.4);
      color: #a5b4fc;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 12px;
      border-radius: 12px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
  </style>
</head>
<body>

  <div class="calc-container">
    <div class="calc-header">
      <div class="header-left">
        <span class="brand">Pay-Per-Calc</span>
        <button class="info-btn" onclick="openAboutModal()" title="About Developer">i</button>
      </div>
      <span class="status-tag">PRO 2026</span>
    </div>

    <div class="display">
      <div class="expression" id="expression">0</div>
      <div class="result" id="result">₱0.00</div>
    </div>

    <div class="keypad">
      <button class="btn clear" onclick="clearCalc()">C</button>
      <button class="btn op" onclick="appendInput('(')">(</button>
      <button class="btn op" onclick="appendInput(')')">)</button>
      <button class="btn op" onclick="appendInput('/')">÷</button>

      <button class="btn" onclick="appendInput('7')">7</button>
      <button class="btn" onclick="appendInput('8')">8</button>
      <button class="btn" onclick="appendInput('9')">9</button>
      <button class="btn op" onclick="appendInput('*')">×</button>

      <button class="btn" onclick="appendInput('4')">4</button>
      <button class="btn" onclick="appendInput('5')">5</button>
      <button class="btn" onclick="appendInput('6')">6</button>
      <button class="btn op" onclick="appendInput('-')">-</button>

      <button class="btn" onclick="appendInput('1')">1</button>
      <button class="btn" onclick="appendInput('2')">2</button>
      <button class="btn" onclick="appendInput('3')">3</button>
      <button class="btn op" onclick="appendInput('+')">+</button>

      <button class="btn" onclick="appendInput('0')">0</button>
      <button class="btn" onclick="appendInput('.')">.</button>
      <button class="btn equals" onclick="triggerPaywall()">=</button>
    </div>
  </div>

  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      
      <!-- Option Selection View -->
      <div id="selectionView">
        <div class="modal-header">
          <div class="modal-title">Unlock Calculation</div>
          <div class="modal-sub">Authorization required to reveal result</div>
        </div>

        <div class="receipt">
          <div class="receipt-row"><span>Base Math Processing</span><span>₱10.00</span></div>
          <div class="receipt-row"><span>Convenience Surge Fee</span><span>₱9.99</span></div>
          <div class="receipt-row total"><span>Total Payable</span><span>₱19.99</span></div>
        </div>

        <div class="payment-grid">
          <!-- GCash Card -->
          <div class="pay-card" onclick="payWith('GCash')">
            <div class="brand-icon gcash">
              <img src="gcash-logo.png" alt="GCash Logo">
            </div>
            <span style="font-size:13px; font-weight:600;">GCash</span>
          </div>

          <!-- Maya Card -->
          <div class="pay-card" onclick="payWith('Maya')">
            <div class="brand-icon maya">
              <img src="maya-logo.png" alt="Maya Logo">
            </div>
            <span style="font-size:13px; font-weight:600;">Maya</span>
          </div>

          <!-- PayPal Card -->
          <div class="pay-card" onclick="payWith('PayPal')">
            <div class="brand-icon paypal">
              <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M35 20H55C68 20 75 26 73 38C71 50 61 56 49 56H40L35 80H22L35 20Z" fill="#003087"/>
                <path d="M42 32H62C72 32 78 37 76 47C74 58 64 64 54 64H47L42 88H31L42 32Z" fill="#0079C1" opacity="0.85"/>
              </svg>
            </div>
            <span style="font-size:13px; font-weight:600;">PayPal</span>
          </div>

          <!-- Credit/Debit Card -->
          <div class="pay-card" onclick="showCardForm()">
            <div class="brand-icon card">
              <svg viewBox="0 0 100 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="38" cy="30" r="18" fill="#EB001B"/>
                <circle cx="62" cy="30" r="18" fill="#F79E1B"/>
                <path d="M50 16.2A17.9 17.9 0 0158.8 30 17.9 17.9 0 0150 43.8 17.9 17.9 0 0141.2 30 17.9 17.9 0 0150 16.2Z" fill="#FF5F00"/>
              </svg>
            </div>
            <span style="font-size:13px; font-weight:600;">Credit / Debit</span>
          </div>
        </div>

        <button class="cancel-btn" onclick="closeModal()">Cancel Transaction</button>
      </div>

      <!-- Card Input Form View -->
      <div class="card-form-view" id="cardFormView">
        <div class="modal-header">
          <div class="modal-title">Enter Card Details</div>
          <div class="modal-sub">Total amount to pay: ₱19.99</div>
        </div>

        <form onsubmit="submitCardPayment(event)">
          <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" class="form-input" placeholder="Juan Dela Cruz" required>
          </div>

          <div class="form-group" style="margin-top: 10px;">
            <label>Card Number (16 Digits)</label>
            <input type="text" id="cardNumber" class="form-input" placeholder="4532 0000 0000 8901" maxlength="19" required>
          </div>

          <div class="form-row" style="margin-top: 10px;">
            <div class="form-group">
              <label>Expiry Date</label>
              <input type="text" id="cardExpiry" class="form-input" placeholder="MM/YY" maxlength="5" required>
            </div>
            <div class="form-group">
              <label>CVC / CVV</label>
              <input type="password" class="form-input" placeholder="123" maxlength="4" required>
            </div>
          </div>

          <button type="submit" class="submit-btn" style="margin-top: 18px;">Pay ₱19.99</button>
          <button type="button" class="cancel-btn" onclick="backToSelection()">Back to Options</button>
        </form>
      </div>

      <!-- Processing View -->
      <div class="processing-view" id="processingView">
        <div class="spinner"></div>
        <div class="modal-title" id="gatewayText">Processing Payment...</div>
      </div>

      <!-- Success View -->
      <div class="success-view" id="successView">
        <div class="success-icon-box">
          <svg class="checkmark-svg" viewBox="0 0 52 52">
            <path class="checkmark-path" d="M14 27 l10 10 l20 -20" />
          </svg>
        </div>
        <div style="color:#34d399; font-size:12px; font-weight:700;">PAYMENT SUCCESSFUL</div>
        <div class="modal-title">Calculation Unlocked!</div>
        <div class="unlocked-value" id="modalUnlockedResult">0</div>
        <div class="modal-sub" id="paymentDetailsSub">Paid ₱19.99</div>

        <button class="submit-btn" onclick="closeModal()">Done</button>
      </div>

      <!-- About Developer View -->
      <div class="about-view" id="aboutView">
        <div class="dev-avatar">JB</div>
        <span class="dev-badge">Lead Developer</span>
        <div class="modal-title" style="margin-top: 4px;">Louie</div>
        <div class="modal-sub" style="max-width: 280px; line-height: 1.5;">
          Creator of Pay-Per-Calc 3000 — turn math operations into microtransactions!
        </div>
        <button class="submit-btn" onclick="closeModal()" style="margin-top: 12px;">Close</button>
      </div>

    </div>
  </div>

  <script>
    let currentInput = "0";
    let calculatedResult = null;
    let audioCtx = null;

    const expressionEl = document.getElementById('expression');
    const resultEl = document.getElementById('result');
    const modalOverlay = document.getElementById('modalOverlay');
    const selectionView = document.getElementById('selectionView');
    const cardFormView = document.getElementById('cardFormView');
    const processingView = document.getElementById('processingView');
    const successView = document.getElementById('successView');
    const aboutView = document.getElementById('aboutView');
    const gatewayText = document.getElementById('gatewayText');
    const modalUnlockedResult = document.getElementById('modalUnlockedResult');
    const paymentDetailsSub = document.getElementById('paymentDetailsSub');

    // 16-digit Card Number auto-spacing
    const cardNumberInput = document.getElementById('cardNumber');
    if (cardNumberInput) {
      cardNumberInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '').substring(0, 16);
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || '';
        e.target.value = formattedValue;
      });
    }

    // Expiry Date auto-slash (MM/YY)
    const cardExpiryInput = document.getElementById('cardExpiry');
    if (cardExpiryInput) {
      cardExpiryInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '').substring(0, 4);
        if (value.length >= 3) {
          e.target.value = value.substring(0, 2) + '/' + value.substring(2);
        } else {
          e.target.value = value;
        }
      });
    }

    function hideAllModalViews() {
      selectionView.style.display = "none";
      cardFormView.style.display = "none";
      processingView.style.display = "none";
      successView.style.display = "none";
      aboutView.style.display = "none";
    }

    function openAboutModal() {
      playButtonClickAudio(600);
      hideAllModalViews();
      aboutView.style.display = "flex";
      modalOverlay.classList.add('active');
    }

    function initAudio() {
      if (!audioCtx) {
        audioCtx = new (window.AudioContext || window.webkitAudioContext)();
      }
      if (audioCtx.state === 'suspended') {
        audioCtx.resume();
      }
    }

    function playButtonClickAudio(freq = 600) {
      try {
        initAudio();
        const now = audioCtx.currentTime;

        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();

        osc.type = 'sine';
        osc.frequency.setValueAtTime(freq, now);
        osc.frequency.exponentialRampToValueAtTime(150, now + 0.05);

        gain.gain.setValueAtTime(0.12, now);
        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);

        osc.connect(gain);
        gain.connect(audioCtx.destination);

        osc.start(now);
        osc.stop(now + 0.05);
      } catch (e) {
        console.warn("Audio unavailable", e);
      }
    }

    function appendInput(val) {
      playButtonClickAudio(550);
      resultEl.classList.remove('unlocked');
      if (currentInput === "0" && !isNaN(val)) {
        currentInput = val;
      } else {
        currentInput += val;
      }
      expressionEl.innerText = currentInput;
    }

    function clearCalc() {
      playButtonClickAudio(400);
      currentInput = "0";
      expressionEl.innerText = "0";
      resultEl.innerText = "₱0.00";
      resultEl.classList.remove('unlocked');
    }

    function triggerPaywall() {
      playButtonClickAudio(800);
      try {
        const cleanExpr = currentInput.replace(/×/g, '*').replace(/÷/g, '/');
        calculatedResult = Function('"use strict"; return (' + cleanExpr + ')')();

        hideAllModalViews();
        selectionView.style.display = "block";
        modalOverlay.classList.add('active');
      } catch (e) {
        resultEl.innerText = "Error";
      }
    }

    function showCardForm() {
      playButtonClickAudio(650);
      hideAllModalViews();
      cardFormView.style.display = "flex";
    }

    function backToSelection() {
      playButtonClickAudio(450);
      hideAllModalViews();
      selectionView.style.display = "block";
    }

    function submitCardPayment(e) {
      e.preventDefault();
      payWith('Credit/Debit Card');
    }

    function closeModal() {
      playButtonClickAudio(450);
      modalOverlay.classList.remove('active');
    }

    function playPaymentSuccessAudio() {
      try {
        initAudio();
        const now = audioCtx.currentTime;

        const osc1 = audioCtx.createOscillator();
        const gain1 = audioCtx.createGain();
        osc1.frequency.setValueAtTime(1318.51, now);
        gain1.gain.setValueAtTime(0.2, now);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.25);
        osc1.connect(gain1);
        gain1.connect(audioCtx.destination);
        osc1.start(now);
        osc1.stop(now + 0.25);

        const osc2 = audioCtx.createOscillator();
        const gain2 = audioCtx.createGain();
        osc2.frequency.setValueAtTime(1760.00, now + 0.1);
        gain2.gain.setValueAtTime(0.2, now + 0.1);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
        osc2.connect(gain2);
        gain2.connect(audioCtx.destination);
        osc2.start(now + 0.1);
        osc2.stop(now + 0.5);
      } catch (e) {
        console.warn("Audio unavailable", e);
      }
    }

    function payWith(provider) {
      playButtonClickAudio(700);
      hideAllModalViews();
      processingView.style.display = "flex";
      gatewayText.innerText = `Authorizing ${provider}...`;

      setTimeout(() => {
        hideAllModalViews();
        successView.style.display = "flex";
        
        modalUnlockedResult.innerText = calculatedResult;
        paymentDetailsSub.innerText = `Paid ₱19.99 via ${provider}`;
        
        resultEl.innerText = calculatedResult;
        resultEl.classList.add('unlocked');

        playPaymentSuccessAudio();
      }, 1500);
    }
  </script>
</body>
</html>