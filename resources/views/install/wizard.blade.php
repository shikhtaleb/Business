<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Install — Retont Business CMS</title>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            DEFAULT: '#FF8528',
            50:  '#fff5ec',
            100: '#ffe6ce',
            200: '#ffca9a',
            300: '#ffaa5e',
            400: '#ff8528',
            500: '#f96700',
            600: '#cc4e00',
            700: '#a33b00',
            800: '#7a2c00',
            900: '#521e00',
          }
        }
      }
    }
  }
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
  [x-cloak] { display: none !important; }
  body { font-family: 'Inter', system-ui, sans-serif; }
  .step-ring {
    width: 2.25rem; height: 2.25rem;
    border-radius: 9999px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.875rem; font-weight: 600;
    flex-shrink: 0;
    transition: all 0.2s;
  }
</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-amber-50">

<div
  x-data="{
    step: 1,
    totalSteps: 5,

    /* step 1 */
    language: 'en',

    /* step 2 */
    db: { host: '127.0.0.1', port: '3306', name: '', username: '', password: '' },
    dbTesting: false,
    dbTested: false,
    dbOk: false,
    dbMessage: '',

    /* step 3 */
    site: { name: 'Retont Business', url: '' },

    /* step 4 */
    admin: { name: '', email: '', password: '', confirm: '' },
    passwordMismatch: false,

    /* step 5 */
    installing: false,
    installDone: false,
    installError: '',
    installProgress: '',
    installMessages: ['Running migrations...', 'Seeding default content...', 'Creating admin account...'],
    installMsgIdx: 0,
    installMsgTimer: null,

    languages: [
      { code: 'ar', label: 'العربية',    flag: '🇸🇦' },
      { code: 'en', label: 'English',    flag: '🇬🇧' },
      { code: 'nl', label: 'Nederlands', flag: '🇳🇱' },
      { code: 'de', label: 'Deutsch',    flag: '🇩🇪' },
    ],

    get progressPct() {
      return Math.round(((this.step - 1) / (this.totalSteps - 1)) * 100);
    },

    goNext() {
      if (this.step === 4) {
        this.passwordMismatch = this.admin.password !== this.admin.confirm;
        if (this.passwordMismatch) return;
        this.step = 5;
        this.startInstall();
        return;
      }
      if (this.step < this.totalSteps) this.step++;
    },

    goBack() {
      if (this.step > 1) this.step--;
    },

    async testConnection() {
      this.dbTesting = true;
      this.dbTested = false;
      this.dbOk = false;
      this.dbMessage = '';
      const csrf = document.querySelector('meta[name=csrf-token]').content;
      try {
        const res = await fetch('/install/test-connection', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify(this.db)
        });
        const data = await res.json();
        this.dbOk = data.success === true;
        this.dbMessage = data.message || (this.dbOk ? 'Connection successful!' : 'Connection failed.');
      } catch (e) {
        this.dbOk = false;
        this.dbMessage = 'Network error: ' + e.message;
      } finally {
        this.dbTesting = false;
        this.dbTested = true;
      }
    },

    startInstall() {
      this.installing = true;
      this.installDone = false;
      this.installError = '';
      this.installMsgIdx = 0;
      this.installProgress = this.installMessages[0];

      this.installMsgTimer = setInterval(() => {
        if (this.installMsgIdx < this.installMessages.length - 1) {
          this.installMsgIdx++;
          this.installProgress = this.installMessages[this.installMsgIdx];
        }
      }, 1800);

      const csrf = document.querySelector('meta[name=csrf-token]').content;
      fetch('/install/run', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({
          language: this.language,
          db: this.db,
          site: this.site,
          admin: this.admin
        })
      })
      .then(r => r.json())
      .then(data => {
        clearInterval(this.installMsgTimer);
        this.installing = false;
        if (data.success) {
          this.installProgress = 'Installation complete!';
          this.installDone = true;
        } else {
          this.installError = data.message || 'Installation failed. Please try again.';
        }
      })
      .catch(e => {
        clearInterval(this.installMsgTimer);
        this.installing = false;
        this.installError = 'Network error: ' + e.message;
      });
    }
  }"
  x-cloak
  class="min-h-screen flex flex-col items-center justify-start py-10 px-4"
>

  <!-- Header -->
  <div class="mb-8 text-center">
    <div class="inline-flex items-center gap-2 mb-2">
      <div class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      </div>
      <span class="text-xl font-bold text-gray-800">Retont Business</span>
    </div>
    <p class="text-sm text-gray-500">CMS Installation Wizard</p>
  </div>

  <!-- Card -->
  <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

    <!-- Step indicator -->
    <div class="px-8 pt-7 pb-4 border-b border-gray-100">
      <div class="flex items-center justify-between mb-4">
        <template x-for="n in totalSteps" :key="n">
          <div class="flex items-center" :class="n < totalSteps ? 'flex-1' : ''">
            <div
              class="step-ring border-2 transition-all"
              :class="{
                'bg-orange-500 border-orange-500 text-white': n < step,
                'border-orange-500 text-orange-500 bg-white': n === step,
                'border-gray-200 text-gray-400 bg-white': n > step
              }"
              x-text="n < step ? '✓' : n"
            ></div>
            <div
              x-show="n < totalSteps"
              class="flex-1 h-0.5 mx-1 transition-all"
              :class="n < step ? 'bg-orange-500' : 'bg-gray-200'"
            ></div>
          </div>
        </template>
      </div>
      <!-- Progress bar -->
      <div class="w-full bg-gray-100 rounded-full h-1.5">
        <div
          class="bg-orange-500 h-1.5 rounded-full transition-all duration-500"
          :style="'width:' + progressPct + '%'"
        ></div>
      </div>
    </div>

    <!-- Step content -->
    <div class="px-8 py-7">

      <!-- ── STEP 1: Welcome / Language ── -->
      <div x-show="step === 1">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Welcome to Retont Business CMS</h2>
        <p class="text-gray-500 text-sm mb-6">
          This wizard will guide you through the initial setup. It will configure your database,
          create your admin account, and seed default content. The whole process takes about
          a minute. Please select your preferred language to get started.
        </p>
        <div class="grid grid-cols-2 gap-3 mb-8">
          <template x-for="lang in languages" :key="lang.code">
            <button
              @click="language = lang.code"
              class="flex items-center gap-3 p-4 rounded-xl border-2 transition-all text-left"
              :class="language === lang.code
                ? 'border-orange-500 bg-orange-50'
                : 'border-gray-200 hover:border-orange-300 hover:bg-orange-50/30'"
            >
              <span class="text-2xl leading-none" x-text="lang.flag"></span>
              <div>
                <div class="font-semibold text-gray-800 text-sm" x-text="lang.label"></div>
                <div class="text-xs text-gray-400 uppercase" x-text="lang.code"></div>
              </div>
              <span
                x-show="language === lang.code"
                class="ms-auto w-5 h-5 rounded-full bg-orange-500 flex items-center justify-center text-white text-xs"
              >✓</span>
            </button>
          </template>
        </div>
      </div>

      <!-- ── STEP 2: Database ── -->
      <div x-show="step === 2">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Database Configuration</h2>
        <p class="text-gray-500 text-sm mb-6">Enter your MySQL / MariaDB connection details. The database must already exist.</p>
        <div class="space-y-4 mb-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">DB Host</label>
              <input
                type="text"
                x-model="db.host"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                placeholder="127.0.0.1"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">DB Port</label>
              <input
                type="text"
                x-model="db.port"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                placeholder="3306"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
            <input
              type="text"
              x-model="db.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="retont_business"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">DB Username</label>
            <input
              type="text"
              x-model="db.username"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="root"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">DB Password</label>
            <input
              type="password"
              x-model="db.password"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="••••••••"
            />
          </div>
        </div>

        <button
          @click="testConnection()"
          :disabled="dbTesting || !db.name || !db.username"
          class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all mb-4"
          :class="dbTesting || !db.name || !db.username
            ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
            : 'bg-orange-100 text-orange-700 hover:bg-orange-200'"
        >
          <span x-show="!dbTesting">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </span>
          <span x-show="dbTesting">
            <svg class="animate-spin" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
          </span>
          <span x-text="dbTesting ? 'Testing...' : 'Test Connection'"></span>
        </button>

        <!-- Connection result -->
        <div x-show="dbTested" x-transition class="flex items-start gap-2 p-3 rounded-lg text-sm mb-2"
          :class="dbOk ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'"
        >
          <span x-show="dbOk" class="mt-0.5">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
          </span>
          <span x-show="!dbOk" class="mt-0.5">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
          </span>
          <span x-text="dbMessage"></span>
        </div>
      </div>

      <!-- ── STEP 3: Site Information ── -->
      <div x-show="step === 3">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Site Information</h2>
        <p class="text-gray-500 text-sm mb-6">Basic details about your website that will be stored in settings.</p>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
            <input
              type="text"
              x-model="site.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="Retont Business"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Site URL</label>
            <input
              type="url"
              x-model="site.url"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="https://example.com"
            />
            <p class="text-xs text-gray-400 mt-1">No trailing slash. Used for generating absolute URLs.</p>
          </div>
        </div>
      </div>

      <!-- ── STEP 4: Admin Account ── -->
      <div x-show="step === 4">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Create Admin Account</h2>
        <p class="text-gray-500 text-sm mb-6">This account will have full administrator access to the CMS.</p>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
            <input
              type="text"
              x-model="admin.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="Administrator"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
            <input
              type="email"
              x-model="admin.email"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="admin@example.com"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              x-model="admin.password"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="Min. 8 characters"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
              type="password"
              x-model="admin.confirm"
              @input="passwordMismatch = false"
              class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              :class="passwordMismatch ? 'border-red-400 bg-red-50' : 'border-gray-300'"
              placeholder="Repeat your password"
            />
            <p x-show="passwordMismatch" class="text-xs text-red-500 mt-1">Passwords do not match.</p>
          </div>
        </div>
      </div>

      <!-- ── STEP 5: Installing ── -->
      <div x-show="step === 5">
        <!-- Installing spinner -->
        <div x-show="installing" class="py-8 text-center">
          <div class="flex justify-center mb-5">
            <div class="relative w-16 h-16">
              <svg class="animate-spin w-16 h-16 text-orange-500" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="28" stroke="currentColor" stroke-width="5" stroke-opacity="0.2"/>
                <path d="M32 4 a28 28 0 0 1 28 28" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
              </svg>
              <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                </div>
              </div>
            </div>
          </div>
          <h2 class="text-xl font-bold text-gray-800 mb-2">Installing…</h2>
          <p class="text-gray-500 text-sm" x-text="installProgress"></p>
          <div class="flex justify-center gap-1 mt-5">
            <template x-for="(msg, idx) in installMessages" :key="idx">
              <div
                class="h-1.5 rounded-full transition-all duration-500"
                :class="idx <= installMsgIdx ? 'bg-orange-500 w-6' : 'bg-gray-200 w-3'"
              ></div>
            </template>
          </div>
        </div>

        <!-- Success -->
        <div x-show="installDone && !installing" x-transition class="py-8 text-center">
          <div class="flex justify-center mb-5">
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
              <svg class="text-green-500" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            </div>
          </div>
          <h2 class="text-2xl font-bold text-gray-800 mb-2">Installation Complete!</h2>
          <p class="text-gray-500 text-sm mb-6">
            Retont Business CMS has been successfully installed.
            The install directory has been locked. You can now log in to the admin panel.
          </p>
          <a
            href="/admin/login"
            class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-colors"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            Go to Admin Login
          </a>
        </div>

        <!-- Error -->
        <div x-show="installError && !installing" x-transition class="py-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
              <svg class="text-red-500" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
            </div>
            <div>
              <h2 class="text-lg font-bold text-gray-800">Installation Failed</h2>
              <p class="text-sm text-red-600" x-text="installError"></p>
            </div>
          </div>
          <button
            @click="step = 1; installError = ''; dbTested = false; dbOk = false;"
            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors"
          >
            ← Start Over
          </button>
        </div>
      </div>

    </div><!-- /px-8 -->

    <!-- Footer / navigation -->
    <div
      x-show="step < 5"
      class="px-8 py-5 border-t border-gray-100 flex items-center"
      :class="step > 1 ? 'justify-between' : 'justify-end'"
    >
      <!-- Back -->
      <button
        x-show="step > 1"
        @click="goBack()"
        class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
      >
        ← Back
      </button>

      <!-- Next / Install Now -->
      <button
        @click="goNext()"
        :disabled="
          (step === 2 && !dbOk) ||
          (step === 3 && (!site.name || !site.url)) ||
          (step === 4 && (!admin.name || !admin.email || !admin.password || !admin.confirm))
        "
        class="flex items-center gap-2 px-5 py-2.5 bg-orange-500 text-white text-sm font-semibold rounded-xl hover:bg-orange-600 transition-colors disabled:opacity-40 disabled:cursor-not-allowed"
      >
        <span x-text="step === 4 ? 'Install Now' : 'Next Step'"></span>
        <svg x-show="step < 4" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        <svg x-show="step === 4" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      </button>
    </div>

  </div><!-- /card -->

  <!-- Step labels -->
  <div class="mt-4 flex items-center gap-6 text-xs text-gray-400">
    <span :class="step === 1 ? 'text-orange-600 font-medium' : ''">1. Language</span>
    <span :class="step === 2 ? 'text-orange-600 font-medium' : ''">2. Database</span>
    <span :class="step === 3 ? 'text-orange-600 font-medium' : ''">3. Site Info</span>
    <span :class="step === 4 ? 'text-orange-600 font-medium' : ''">4. Admin</span>
    <span :class="step === 5 ? 'text-orange-600 font-medium' : ''">5. Install</span>
  </div>

</div><!-- /x-data -->
</body>
</html>
