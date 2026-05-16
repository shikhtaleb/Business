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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap">
<style>
  [x-cloak] { display: none !important; }
  body { font-family: 'Inter', system-ui, sans-serif; }
  html[dir="rtl"] body { font-family: 'IBM Plex Sans Arabic', system-ui, sans-serif; }
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
    installMsgIdx: 0,
    installMsgTimer: null,

    languages: [
      { code: 'ar', label: 'العربية',    flag: '🇸🇦' },
      { code: 'en', label: 'English',    flag: '🇬🇧' },
      { code: 'nl', label: 'Nederlands', flag: '🇳🇱' },
      { code: 'de', label: 'Deutsch',    flag: '🇩🇪' },
    ],

    i18n: {
      ar: {
        title: 'برنامج التثبيت — Retont Business CMS',
        brand: 'Retont Business',
        subtitle: 'معالج تثبيت نظام إدارة المحتوى',
        s1_heading: 'مرحبًا بك في Retont Business CMS',
        s1_desc: 'سيقوم هذا المعالج بإرشادك خلال الإعداد الأولي. سيهيّئ قاعدة البيانات، وينشئ حساب المدير، ويزرع المحتوى الافتراضي. تستغرق العملية حوالي دقيقة. اختر اللغة المفضّلة لديك للبدء.',
        s2_heading: 'إعدادات قاعدة البيانات',
        s2_desc: 'أدخل تفاصيل الاتصال بـ MySQL / MariaDB. يجب أن تكون قاعدة البيانات موجودة مسبقًا.',
        db_host: 'مضيف قاعدة البيانات',
        db_port: 'منفذ قاعدة البيانات',
        db_name: 'اسم قاعدة البيانات',
        db_user: 'اسم المستخدم',
        db_pass: 'كلمة المرور',
        test_conn: 'اختبار الاتصال',
        testing: 'جارٍ الاختبار…',
        conn_ok: 'تم الاتصال بنجاح!',
        conn_fail: 'فشل الاتصال.',
        s3_heading: 'معلومات الموقع',
        s3_desc: 'تفاصيل أساسية عن موقعك ستُحفظ في الإعدادات.',
        site_name: 'اسم الموقع',
        site_url: 'رابط الموقع',
        site_url_hint: 'بدون شرطة مائلة في النهاية. يُستخدم لإنشاء الروابط المطلقة.',
        s4_heading: 'إنشاء حساب المدير',
        s4_desc: 'سيتمتع هذا الحساب بصلاحيات إدارية كاملة على النظام.',
        full_name: 'الاسم الكامل',
        email_addr: 'البريد الإلكتروني',
        password: 'كلمة المرور',
        confirm_pass: 'تأكيد كلمة المرور',
        pass_min: '٨ أحرف على الأقل',
        pass_repeat: 'أعد إدخال كلمة المرور',
        pass_mismatch: 'كلمتا المرور غير متطابقتين.',
        ph_admin: 'مدير النظام',
        ph_email: 'admin@example.com',
        installing: 'جارٍ التثبيت…',
        msg_migrate: 'تشغيل عمليات الترحيل…',
        msg_seed: 'زرع المحتوى الافتراضي…',
        msg_admin: 'إنشاء حساب المدير…',
        install_complete: 'اكتمل التثبيت!',
        install_done_desc: 'تم تثبيت Retont Business CMS بنجاح. تم قفل مجلد التثبيت. يمكنك الآن تسجيل الدخول إلى لوحة التحكم.',
        go_login: 'الانتقال لتسجيل الدخول',
        install_failed: 'فشل التثبيت',
        start_over: '↻ ابدأ من جديد',
        back: 'رجوع',
        next: 'الخطوة التالية',
        install_now: 'ثبّت الآن',
        step1: '١. اللغة',
        step2: '٢. قاعدة البيانات',
        step3: '٣. معلومات الموقع',
        step4: '٤. المدير',
        step5: '٥. التثبيت',
        net_err: 'خطأ في الشبكة: ',
        install_fail_default: 'فشل التثبيت. حاول مرة أخرى.'
      },
      en: {
        title: 'Install — Retont Business CMS',
        brand: 'Retont Business',
        subtitle: 'CMS Installation Wizard',
        s1_heading: 'Welcome to Retont Business CMS',
        s1_desc: 'This wizard will guide you through the initial setup. It will configure your database, create your admin account, and seed default content. The whole process takes about a minute. Please select your preferred language to get started.',
        s2_heading: 'Database Configuration',
        s2_desc: 'Enter your MySQL / MariaDB connection details. The database must already exist.',
        db_host: 'DB Host',
        db_port: 'DB Port',
        db_name: 'Database Name',
        db_user: 'DB Username',
        db_pass: 'DB Password',
        test_conn: 'Test Connection',
        testing: 'Testing…',
        conn_ok: 'Connection successful!',
        conn_fail: 'Connection failed.',
        s3_heading: 'Site Information',
        s3_desc: 'Basic details about your website that will be stored in settings.',
        site_name: 'Site Name',
        site_url: 'Site URL',
        site_url_hint: 'No trailing slash. Used for generating absolute URLs.',
        s4_heading: 'Create Admin Account',
        s4_desc: 'This account will have full administrator access to the CMS.',
        full_name: 'Full Name',
        email_addr: 'Email Address',
        password: 'Password',
        confirm_pass: 'Confirm Password',
        pass_min: 'Min. 8 characters',
        pass_repeat: 'Repeat your password',
        pass_mismatch: 'Passwords do not match.',
        ph_admin: 'Administrator',
        ph_email: 'admin@example.com',
        installing: 'Installing…',
        msg_migrate: 'Running migrations…',
        msg_seed: 'Seeding default content…',
        msg_admin: 'Creating admin account…',
        install_complete: 'Installation Complete!',
        install_done_desc: 'Retont Business CMS has been successfully installed. The install directory has been locked. You can now log in to the admin panel.',
        go_login: 'Go to Admin Login',
        install_failed: 'Installation Failed',
        start_over: '← Start Over',
        back: '← Back',
        next: 'Next Step',
        install_now: 'Install Now',
        step1: '1. Language',
        step2: '2. Database',
        step3: '3. Site Info',
        step4: '4. Admin',
        step5: '5. Install',
        net_err: 'Network error: ',
        install_fail_default: 'Installation failed. Please try again.'
      },
      nl: {
        title: 'Installatie — Retont Business CMS',
        brand: 'Retont Business',
        subtitle: 'CMS Installatiewizard',
        s1_heading: 'Welkom bij Retont Business CMS',
        s1_desc: 'Deze wizard begeleidt u bij de eerste installatie. Hij configureert uw database, maakt uw beheerdersaccount aan en plaatst standaardinhoud. Het hele proces duurt ongeveer een minuut. Selecteer uw gewenste taal om te beginnen.',
        s2_heading: 'Database configuratie',
        s2_desc: 'Voer uw MySQL / MariaDB verbindingsgegevens in. De database moet al bestaan.',
        db_host: 'DB Host',
        db_port: 'DB Poort',
        db_name: 'Databasenaam',
        db_user: 'DB Gebruikersnaam',
        db_pass: 'DB Wachtwoord',
        test_conn: 'Verbinding testen',
        testing: 'Bezig met testen…',
        conn_ok: 'Verbinding gelukt!',
        conn_fail: 'Verbinding mislukt.',
        s3_heading: 'Site-informatie',
        s3_desc: 'Basisgegevens over uw website die in de instellingen worden opgeslagen.',
        site_name: 'Sitenaam',
        site_url: 'Site URL',
        site_url_hint: 'Geen schuine streep aan het einde. Gebruikt voor het genereren van absolute URLs.',
        s4_heading: 'Beheerdersaccount aanmaken',
        s4_desc: 'Dit account heeft volledige beheerderstoegang tot het CMS.',
        full_name: 'Volledige naam',
        email_addr: 'E-mailadres',
        password: 'Wachtwoord',
        confirm_pass: 'Wachtwoord bevestigen',
        pass_min: 'Min. 8 tekens',
        pass_repeat: 'Herhaal uw wachtwoord',
        pass_mismatch: 'Wachtwoorden komen niet overeen.',
        ph_admin: 'Beheerder',
        ph_email: 'admin@example.com',
        installing: 'Bezig met installeren…',
        msg_migrate: 'Migraties uitvoeren…',
        msg_seed: 'Standaardinhoud plaatsen…',
        msg_admin: 'Beheerdersaccount aanmaken…',
        install_complete: 'Installatie voltooid!',
        install_done_desc: 'Retont Business CMS is succesvol geïnstalleerd. De installatiemap is vergrendeld. U kunt nu inloggen in het beheerderspaneel.',
        go_login: 'Naar beheerdersinlog',
        install_failed: 'Installatie mislukt',
        start_over: '← Opnieuw beginnen',
        back: '← Terug',
        next: 'Volgende stap',
        install_now: 'Nu installeren',
        step1: '1. Taal',
        step2: '2. Database',
        step3: '3. Site-info',
        step4: '4. Beheerder',
        step5: '5. Installatie',
        net_err: 'Netwerkfout: ',
        install_fail_default: 'Installatie mislukt. Probeer het opnieuw.'
      },
      de: {
        title: 'Installation — Retont Business CMS',
        brand: 'Retont Business',
        subtitle: 'CMS Installationsassistent',
        s1_heading: 'Willkommen bei Retont Business CMS',
        s1_desc: 'Dieser Assistent führt Sie durch die Ersteinrichtung. Er konfiguriert Ihre Datenbank, erstellt Ihr Administratorkonto und legt Standardinhalte an. Der gesamte Vorgang dauert etwa eine Minute. Bitte wählen Sie Ihre bevorzugte Sprache, um zu beginnen.',
        s2_heading: 'Datenbankkonfiguration',
        s2_desc: 'Geben Sie Ihre MySQL / MariaDB Verbindungsdaten ein. Die Datenbank muss bereits existieren.',
        db_host: 'DB Host',
        db_port: 'DB Port',
        db_name: 'Datenbankname',
        db_user: 'DB Benutzername',
        db_pass: 'DB Passwort',
        test_conn: 'Verbindung testen',
        testing: 'Wird getestet…',
        conn_ok: 'Verbindung erfolgreich!',
        conn_fail: 'Verbindung fehlgeschlagen.',
        s3_heading: 'Seiteninformationen',
        s3_desc: 'Grundlegende Details über Ihre Website, die in den Einstellungen gespeichert werden.',
        site_name: 'Seitenname',
        site_url: 'Seiten-URL',
        site_url_hint: 'Kein abschließender Schrägstrich. Wird zum Erstellen absoluter URLs verwendet.',
        s4_heading: 'Administratorkonto erstellen',
        s4_desc: 'Dieses Konto hat vollständigen Administratorzugriff auf das CMS.',
        full_name: 'Vollständiger Name',
        email_addr: 'E-Mail-Adresse',
        password: 'Passwort',
        confirm_pass: 'Passwort bestätigen',
        pass_min: 'Min. 8 Zeichen',
        pass_repeat: 'Wiederholen Sie Ihr Passwort',
        pass_mismatch: 'Passwörter stimmen nicht überein.',
        ph_admin: 'Administrator',
        ph_email: 'admin@example.com',
        installing: 'Wird installiert…',
        msg_migrate: 'Migrationen werden ausgeführt…',
        msg_seed: 'Standardinhalt wird angelegt…',
        msg_admin: 'Administratorkonto wird erstellt…',
        install_complete: 'Installation abgeschlossen!',
        install_done_desc: 'Retont Business CMS wurde erfolgreich installiert. Das Installationsverzeichnis wurde gesperrt. Sie können sich nun im Admin-Panel anmelden.',
        go_login: 'Zur Admin-Anmeldung',
        install_failed: 'Installation fehlgeschlagen',
        start_over: '← Neu beginnen',
        back: '← Zurück',
        next: 'Nächster Schritt',
        install_now: 'Jetzt installieren',
        step1: '1. Sprache',
        step2: '2. Datenbank',
        step3: '3. Seiten-Info',
        step4: '4. Admin',
        step5: '5. Installation',
        net_err: 'Netzwerkfehler: ',
        install_fail_default: 'Installation fehlgeschlagen. Bitte versuchen Sie es erneut.'
      }
    },

    get t() {
      return this.i18n[this.language] || this.i18n.en;
    },

    get isRtl() {
      return this.language === 'ar';
    },

    get installMessages() {
      return [this.t.msg_migrate, this.t.msg_seed, this.t.msg_admin];
    },

    get progressPct() {
      return Math.round(((this.step - 1) / (this.totalSteps - 1)) * 100);
    },

    init() {
      this.$watch('language', (v) => {
        document.documentElement.setAttribute('lang', v);
        document.documentElement.setAttribute('dir', v === 'ar' ? 'rtl' : 'ltr');
        document.title = this.t.title;
      });
      document.documentElement.setAttribute('lang', this.language);
      document.documentElement.setAttribute('dir', this.isRtl ? 'rtl' : 'ltr');
      document.title = this.t.title;
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
        this.dbMessage = data.message || (this.dbOk ? this.t.conn_ok : this.t.conn_fail);
      } catch (e) {
        this.dbOk = false;
        this.dbMessage = this.t.net_err + e.message;
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
          this.installProgress = this.t.install_complete;
          this.installDone = true;
        } else {
          this.installError = data.message || this.t.install_fail_default;
        }
      })
      .catch(e => {
        clearInterval(this.installMsgTimer);
        this.installing = false;
        this.installError = this.t.net_err + e.message;
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
      <span class="text-xl font-bold text-gray-800" x-text="t.brand"></span>
    </div>
    <p class="text-sm text-gray-500" x-text="t.subtitle"></p>
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
        <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="t.s1_heading"></h2>
        <p class="text-gray-500 text-sm mb-6" x-text="t.s1_desc"></p>
        <div class="grid grid-cols-2 gap-3 mb-8">
          <template x-for="lang in languages" :key="lang.code">
            <button
              @click="language = lang.code"
              class="flex items-center gap-3 p-4 rounded-xl border-2 transition-all text-start"
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
        <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="t.s2_heading"></h2>
        <p class="text-gray-500 text-sm mb-6" x-text="t.s2_desc"></p>
        <div class="space-y-4 mb-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.db_host"></label>
              <input
                type="text"
                x-model="db.host"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                placeholder="127.0.0.1"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.db_port"></label>
              <input
                type="text"
                x-model="db.port"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
                placeholder="3306"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.db_name"></label>
            <input
              type="text"
              x-model="db.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="retont_business"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.db_user"></label>
            <input
              type="text"
              x-model="db.username"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="root"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.db_pass"></label>
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
          <span x-text="dbTesting ? t.testing : t.test_conn"></span>
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
        <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="t.s3_heading"></h2>
        <p class="text-gray-500 text-sm mb-6" x-text="t.s3_desc"></p>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.site_name"></label>
            <input
              type="text"
              x-model="site.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="Retont Business"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.site_url"></label>
            <input
              type="url"
              x-model="site.url"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              placeholder="https://example.com"
            />
            <p class="text-xs text-gray-400 mt-1" x-text="t.site_url_hint"></p>
          </div>
        </div>
      </div>

      <!-- ── STEP 4: Admin Account ── -->
      <div x-show="step === 4">
        <h2 class="text-2xl font-bold text-gray-800 mb-1" x-text="t.s4_heading"></h2>
        <p class="text-gray-500 text-sm mb-6" x-text="t.s4_desc"></p>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.full_name"></label>
            <input
              type="text"
              x-model="admin.name"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              :placeholder="t.ph_admin"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.email_addr"></label>
            <input
              type="email"
              x-model="admin.email"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              :placeholder="t.ph_email"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.password"></label>
            <input
              type="password"
              x-model="admin.password"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              :placeholder="t.pass_min"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="t.confirm_pass"></label>
            <input
              type="password"
              x-model="admin.confirm"
              @input="passwordMismatch = false"
              class="w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-orange-400"
              :class="passwordMismatch ? 'border-red-400 bg-red-50' : 'border-gray-300'"
              :placeholder="t.pass_repeat"
            />
            <p x-show="passwordMismatch" class="text-xs text-red-500 mt-1" x-text="t.pass_mismatch"></p>
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
          <h2 class="text-xl font-bold text-gray-800 mb-2" x-text="t.installing"></h2>
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
          <h2 class="text-2xl font-bold text-gray-800 mb-2" x-text="t.install_complete"></h2>
          <p class="text-gray-500 text-sm mb-6" x-text="t.install_done_desc"></p>
          <a
            href="/admin/login"
            class="inline-flex items-center gap-2 px-6 py-3 bg-orange-500 text-white font-semibold rounded-xl hover:bg-orange-600 transition-colors"
          >
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
            <span x-text="t.go_login"></span>
          </a>
        </div>

        <!-- Error -->
        <div x-show="installError && !installing" x-transition class="py-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
              <svg class="text-red-500" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg>
            </div>
            <div>
              <h2 class="text-lg font-bold text-gray-800" x-text="t.install_failed"></h2>
              <p class="text-sm text-red-600" x-text="installError"></p>
            </div>
          </div>
          <button
            @click="step = 1; installError = ''; dbTested = false; dbOk = false;"
            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition-colors"
            x-text="t.start_over"
          ></button>
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
        x-text="t.back"
      ></button>

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
        <span x-text="step === 4 ? t.install_now : t.next"></span>
        <svg x-show="step < 4" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        <svg x-show="step === 4" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      </button>
    </div>

  </div><!-- /card -->

  <!-- Step labels -->
  <div class="mt-4 flex items-center gap-6 text-xs text-gray-400">
    <span :class="step === 1 ? 'text-orange-600 font-medium' : ''" x-text="t.step1"></span>
    <span :class="step === 2 ? 'text-orange-600 font-medium' : ''" x-text="t.step2"></span>
    <span :class="step === 3 ? 'text-orange-600 font-medium' : ''" x-text="t.step3"></span>
    <span :class="step === 4 ? 'text-orange-600 font-medium' : ''" x-text="t.step4"></span>
    <span :class="step === 5 ? 'text-orange-600 font-medium' : ''" x-text="t.step5"></span>
  </div>

</div><!-- /x-data -->
</body>
</html>
